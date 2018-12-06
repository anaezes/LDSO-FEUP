<?php

namespace App\Http\Controllers;

use App\Proposal;
use App\Bid;
use App\User;
use App\Team;
use App\Faculty;
use App\FacultyProposal;
use App\Notification;
use App\NotificationProposal;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mailgun\Mailgun;
use GuzzleHttp\Client;
use Validator;
use Illuminate\Validation\Rule;

class ProposalController extends Controller
{
    private static $lastUpdate = 0;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Shows the proposal for a given id.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $proposal = Proposal::find($id);

        $proposal->duedate = date('Y-m-d', strtotime($proposal->duedate));
        $proposal->announcedate = date('Y-m-d', strtotime($proposal->announcedate));

        $timestamp = ProposalController::createTimestamp($proposal->datecreated, $proposal->duration);

        if ($timestamp === "Proposal has ended!" && $proposal->proposal_status != "evaluated") {
            $proposal->proposal_status = "finished";
        } else {
            if ($proposal->proposal_status != "evaluated") {
                $proposal->proposal_status = "approved";
            }
        }

        $update = ProposalController::updateProposals();

        $facultyNumber = FacultyProposal::where('idproposal', $proposal->id)->get()->first();
        if ($facultyNumber != null) {
            $facultyName = Faculty::where('id', $facultyNumber->idfaculty)->get()->first();
            if ($facultyName != null) {
                $facultyName = $facultyName->facultyname;
            } else {
                $facultyName = "No faculty";
            }
        } else {
            $facultyName = "No faculty";
        }

        $skills = DB::select(
            'SELECT skillname from skill, skill_proposal
                  WHERE skill.id = skill_proposal.idSkill
                  AND skill_proposal.idProposal = ?',
            [$proposal->id]
        );

        $proposal->skills = $skills;

        $bids = DB::select('SELECT id, idteam, biddate from bid WHERE  bid.idProposal = ?', [$proposal->id]);

        foreach ($bids as $bid) {
            $team = Team::where('id', $bid->idteam)->get()->first();
            $bid->teamname = $team->teamname;
            $leader = User::where('id', $team->idleader)->get()->first();
            $bid->teamleaderid = $leader->id;
            $bid->teamleadername = "$leader->username";
        }

        return view(
            'pages.proposal',
            ['proposal' => $proposal,
            'facultyName' => $facultyName,
            'bids' => $bids,
            'timestamp' => $timestamp]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proposal = Proposal::find($id);

        if ($proposal->idproponent != Auth::user()->id) {
            return redirect('/proposal/' . $id);
        }

        return view('pages.proposalEdit', ['proposal' => $proposal]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $proposal = Proposal::find($id);

        $created = strtotime($proposal->datecreated);
        $duedate = strtotime($proposal->duedate);
        $announce = strtotime($proposal->announcedate);
        $duration = $proposal->duration;
        $day = 86400;
        $month = $day * 30;
        $year = $day * 365;

        $validator = Validator::make(
            $request->all(),
            [
            'proposalTitle' => [
                'required',
                'string',
                Rule::unique('proposal', 'title')->ignore($proposal->id)
            ],
            'proposalDescription' => 'required|string|min:20',
            'proposalSkills' => 'array|exists:skill,id',
            'proposalFaculty' => 'array|exists:faculty,id',
            'proposalDueDate' => 'required|date|after:proposalAnnounceDate',
            'proposalAnnounceDate' => 'required|date|after_or_equal:'.date('Y-m-d', $created + $duration)."|before_or_equal:".date('Y-m-d', $created + $duration + 3 * $month)
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $proposal->title = $request->proposalTitle;

        $proposal->description = $request->proposalDescription;

        $skills = $request->input('proposalSkills.*', []);
        foreach ($proposal->skill as $skill) {
            if (!in_array($skill->id, $skills)) {
                $proposal->skill()->detach($skill->id);
            }
        }
        $proposal->skill()->syncWithoutDetaching($skills);


        $faculties = $request->input('proposalFaculty.*', []);
        foreach ($proposal->faculty as $faculty) {
            if (!in_array($faculty->id, $faculties)) {
                $proposal->faculty()->detach($faculty->id);
            }
        }
        $proposal->faculty()->syncWithoutDetaching($faculties);

        $proposal->duedate = $request->proposalDueDate;

        $proposal->announcedate = $request->proposalAnnounceDate;

        $proposal->proposal_public = $request->has('proposalPublic') ? true : false;

        $proposal->bid_public = $request->has('proposalBid') ? true : false;

        $proposal->save();

        return redirect()->route('proposal', [$proposal]);
    }

    /**
     * Updates all proposals, setting them to finished if their time is up and sending out notifications
     */
    public function updateProposals()
    {
        $proposals = DB::select("SELECT id, duration, dateApproved, idproponent FROM proposal WHERE proposal_status = ?", ["approved"]);
        $over = [];

        foreach ($proposals as $proposal) {
            $timestamp = ProposalController::createTimestamp($proposal->datecreated, $proposal->duration);
            if ($timestamp === "Proposal has ended!") {
                array_push($over, $proposal->id);
            }
        }

        if (sizeof($over) == 0) {
            return;
        }

        $parameters = implode(',', $over);
        $query = "UPDATE proposal SET proposal_status = ?, dateFinished = ? WHERE id IN (" . $parameters . ")";
        DB::update($query, ["finished", "now()"]);

        foreach ($over as $id) {
            $this->notifyOwner($id);
            $this->notifyWinnerAndPurchase($id);
            $this->notifyBidders($id);
        }
    }

    /**
     * Notifies the owner of an proposal if it is finished
     *
     * @param  int $id
     * @return 404 if error
     */
    public static function notifyOwner($id)
    {
        try {
            $proposal = Proposal::findOrFail($id);
            $text = "Your proposal ".$proposal->title." has finished!";

            $notification = new Notification;
            $notification->information = $text;
            $notification->idusers = $proposal->idproponent;
            $notification->idproposal = $proposal->id;
            $notification->save();

            $message = "Information of winner:";
            $message .= "\nName: " . $notification->user->name;
            $message .= "\nemail: " . $notification->user->email;
            $message .= "\naddress: " . $notification->user->address;
            $message .= "\npostal code: " . $notification->user->PostalCode;

            // (new ProposalController)::sendMail($message, $proposal->user->email);
        } catch (QueryException $qe) {
            return response('NOT FOUND', 404);
        }
    }

    public static function notifyProponent($id, Request $request)
    {
        try {
            $proposal = Proposal::findOrFail($id);
            DB::table('proposal')->where('proposal.id', '=', $id)->update(['proposal_status' => 'evaluated']);
            $proposal->proposal_status = "evaluated";
            DB::table('bid')->join('proposal', 'proposal.id', '=', 'bid.idproposal')
                ->where([
                    ['proposal.id','=', $id],
                    ['bid.winner','=', true]
                ])
                ->update(['selfevaluation' => $request->input('self_evaluation')]);

            $text = "Your proposal ".$proposal->title." has finished, here is your project(project.rar)!";

            $notification = new Notification;
            $notification->information = $text;
            $notification->idusers = $proposal->idproponent;
            $notification->idproposal = $proposal->id;
            $notification->save();

            $message = "Information of the sender:";
            $message .= "\nName: " . $notification->user->name;
            $message .= "\nemail: " . $notification->user->email;
            $message .= "\naddress: " . $notification->user->address;
            $message .= "\npostal code: " . $notification->user->PostalCode;

            // (new ProposalController)::sendMail($message, $proposal->user->email);
        } catch (QueryException $qe) {
            return response('NOT FOUND', 404);
        }

        return redirect('/proposal/' . $id);
    }

    public function sendMail($message, $email)
    {
        $client = new Client(
            [
            'base_uri' => 'https://api.mailgun.net/v3',
            'verify' => false,
            ]
        );
        $adapter = new \Http\Adapter\Guzzle6\Client($client);
        $domain = "sandboxeb3d0437da8c4b4f8d5a428ed93f64cc.mailgun.org";
        $mailgun = new \Mailgun\Mailgun('key-44a6c35045fe3c3add9fcf0a018e654e', $adapter);

        $result = $mailgun->sendMessage(
            "$domain",
            array('from' => 'Home remote Sandbox <postmaster@sandboxeb3d0437da8c4b4f8d5a428ed93f64cc.mailgun.org>',
                'to' => 'Bookhub seller <' . $email . '>',
                'subject' => 'Proposal information',
                'text' => $message,
                'require_tls' => 'false',
                'skip_verification' => 'true',
            )
        );
    }

    /**
     * Notifies winner and sends an email with purchase info
     *
     * @param  int $id
     * @return 200 if successful, 404 if not
     */
    public static function notifyWinner($id)
    {
        try {
            $proposal = Proposal::findOrFail($id);
            $winner = $proposal->bids()->where('winner', true)->first();

            $text = "You won the proposal ".$proposal->title.".";

            $notification = new Notification;
            $notification->information = $text;
            $notification->idusers = $winner->team->user->id;
            $notification->idproposal = $proposal->id;
            $notification->save();
        } catch (QueryException $qe) {
            return response('NOT FOUND', 404);
        }
        return response('success', 200);
    }

    /**
     * Notifies all bidders if proposal is finished
     *
     * @param  int $id
     * @return 200 if ok, 404 if not
     */
    public static function notifyBidders($id)
    {
        try {
            $proposal = Proposal::findOrFail($id);

            foreach ($proposal->bids as $bid) {
                if (!$bid->winner) {
                    $text = "You lost the proposal for " . $proposal->title . ".";

                    $notification = new Notification;
                    $notification->information = $text;
                    $notification->idusers = $bid->team->user->id;
                    $notification->idproposal = $proposal->id;
                    $notification->save();
                }
            }
        } catch (QueryException $qe) {
            return response('NOT FOUND', 404);
        }
        return response('success', 200);
    }

    /**
     * Creates a timestamp based on a starting date and a duration
     *
     * @param  String $dateCreated
     * @param  int    $duration
     * @return String timestamp
     */
    public static function createTimestamp($dateCreated, $duration)
    {
        $start = strtotime($dateCreated);
        $end = $start + $duration;
        $current = time();
        $time = $end - $current;

        if ($time <= 0) {
            return "Proposal has ended!";
        }

        $ts = "";
        $d = floor($time/86400);
        $ts .= $d . "d ";


        $h = floor(($time-$d*86400)/3600);
        $ts .= $h . "h ";

        $m = floor(($time-($d*86400+$h*3600))/60);
        $ts .= $m . "m ";


        $ts .= $time-($d*86400+$h*3600+$m*60) . "s";



        if (strpos($ts, "0d ") !== false) {
            $ts = str_replace("0d ", "", $ts);
            if (strpos($ts, "0h ") !== false) {
                $ts = str_replace("0h ", "", $ts);
                if (strpos($ts, "0m ") !== false) {
                    $ts = str_replace("0m ", "", $ts);
                    if (strpos($ts, "0s") !== false) {
                        $ts = "Proposal has ended!";
                    }
                }
            }
        }
        return $ts;
    }
}
