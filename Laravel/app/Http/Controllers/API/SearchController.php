<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BidController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Controller;
use App\User;
use App\Team;
use App\Proposal;
use Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
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
      * Does an advanced search
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function search(Request $request)
    {
        try {
            if (!($request->ajax() || $request->pjax())) {
                return response('Forbidden.', 403);
            }

            $queryResults = [];

            if ($request->input('title') != null) {
                $res = DB::select(
                    "SELECT DISTINCT p.id 
                    FROM proposal p
                    WHERE p.title @@ plainto_tsquery('english',?)",
                    [$request->input('title')]
                );
                array_push($queryResults, $res);
            }


            if ($request->input('proposalStatus') != null) {
                $res = DB::select(
                    "SELECT DISTINCT p.id 
                    FROM proposal p 
                    WHERE p.proposal_status = ?",
                    [$request->input('proposalStatus')]
                );
                array_push($queryResults, $res);
            }


            if ($request->input('history') !== null && Auth::check()) {
                $res = DB::table('proposal')
                    ->join('bid', 'proposal.id', '=', 'bid.idproposal')
                    ->join('team', 'bid.idteam', '=', 'team.id')
                    ->join('team_member', 'team.id', '=', 'team_member.idteam')
                    ->where([
                        ['proposal.proposal_status', '=', 'evaluated'],
                        ['bid.winner', '=', true],
                        ['team.idleader', '=', Auth::user()->id],
                    ])
                    ->orWhere([
                        ['proposal.proposal_status', '=', 'evaluated'],
                        ['bid.winner', '=', true],
                        ['team_member.iduser', '=', Auth::user()->id],
                    ])
                    ->select('proposal.id')->get();
                array_push($queryResults, $res);
            }

            if ($request->input('proposalsOfUser') !== null && Auth::check()) {
                $res = DB::select(
                    "SELECT DISTINCT p.id
                    FROM proposal p
                    WHERE p.idproponent = ?",
                    [Auth::id()]
                );
                array_push($queryResults, $res);
            }
            if ($request->input('userBidOn') !== null && Auth::check()) {
                $res = DB::select(
                    "SELECT DISTINCT p.id
                    FROM proposal p
                    INNER JOIN bid b ON b.idproposal = p.id
                    INNER JOIN team t ON b.idteam = t.id
                    INNER JOIN team_member tm ON t.id = tm.idteam
                    WHERE (t.idLeader = ? OR
                        tm.iduser = ?) AND 
                        p.proposal_status IN ('approved', 'finished') AND
                        b.winner = FALSE",
                    [Auth::id(), Auth::id()]
                );
                array_push($queryResults, $res);
            }
            if ($request->input('userBidWinner') !== null && Auth::check()) {
                $res = DB::table('proposal')
                    ->join('bid', 'proposal.id', '=', 'bid.idproposal')
                    ->join('team', 'bid.idteam', '=', 'team.id')
                    ->where([
                            ['team.idleader', '=', Auth::user()->id],
                            ['bid.winner', '=', true],
                            ['proposal.proposal_status', '!=', 'evaluated'],
                        ])
                    ->select('proposal.id')->get();
                array_push($queryResults, $res);
            }
            if ($request->input('proposalsAvailableToUser') !== null) { // todo proposal_status fix later
                if (Auth::check()) {
                    $res = DB::select(
                        "SELECT DISTINCT p.id
                        FROM proposal p
                        INNER JOIN faculty_proposal fp ON fp.idproposal = p.id
                        INNER JOIN users u ON (u.idfaculty = fp.idfaculty OR p.proposal_public = true)
                        WHERE u.id = ? AND 
                                p.proposal_status IN ('approved', 'waitingApproval')",
                        [Auth::id()]
                    );
                    array_push($queryResults, $res);
                } else {
                    $res = DB::select(
                        "SELECT DISTINCT p.id 
                        FROM proposal p 
                        WHERE p.proposal_public = true AND 
                                p.proposal_status IN ('approved', 'waitingApproval')",
                        []
                    );
                    array_push($queryResults, $res);
                }
            }

            $counts = [];
            foreach ($queryResults as $res) {
                foreach ($res as $id) {
                    if (!array_key_exists($id->id, $counts)) {
                        $counts[$id->id] = 1;
                    } else {
                        $counts[$id->id]++;
                    }
                }
            }

            arsort($counts);
            $counts = array_unique(array_keys($counts));

            $ids = implode(",", array_values($counts));
            if ($ids === "") {
                $ids = "-1";
            }
            $query = "SELECT proposal.id, title, duration, dateApproved, proposal_status, datecreated FROM proposal WHERE proposal.id IN (" . $ids . ")";
            $response = DB::select($query, []);

            foreach ($response as $proposal) {
                /*if ($proposal->proposal_status == "waitingApproval") {
                    $proposal->time = "Not yet started";
                } elseif ($proposal->proposal_status == "approved") {
                    $proposal->time = ProposalController::createTimestamp($proposal->datecreated, $proposal->duration);
                } elseif ($proposal->proposal_status == "finished") {
                    $proposal->time = "Finished";
                }*/
                $proposal->nBids = DB::table('proposal')
                ->join('bid', 'proposal.id', '=', 'bid.idproposal')
                ->where([
                    ['proposal.id','=', $proposal->id],
                ])->count();
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return response()->json($response);
    }

    /**
     * Does a general search of users, teams, and proposals
     * by mathcing keywords
     * @param Request $request
     */
    public function generalSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'words' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $words = $request->input('words');

        $users = User::search($words)->get();
        $proposals = Proposal::search($words)->get();
        $teams = Team::search($words)->get();

        foreach ($proposals as $proposal) {
            $proposal->timestamp = ProposalController::createTimestamp($proposal->datecreated, $proposal->duration);
        }

        return view(
            'pages.search',
            ['users' => $users,
            'proposals' => $proposals,
            'teams' => $teams]
        );
    }

    /**
     * Search for users by name, skill or faculty
     * @param Request $request
     */
    public function searchMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'skills' => 'nullable|array|exists:skill,id',
            'faculties' => 'nullable|array|exists:faculty,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $name = $request->has('name') ? $request->input('name') : null;
        $skills = $request->has('skills') ? $request->input('skills') : null;
        $faculties = $request->has('faculties') ? $request->input('faculties') : null;

        if (isset($name) && isset($skills) && isset($faculties)) {
            $users = User::search($name)
                         ->join('skill_user', 'users.id', '=', 'skill_user.iduser')
                         ->whereIn('users.idfaculty', $faculties)
                         ->whereIn('skill_user.idskill', $skills)
                         ->get();
        } elseif (isset($name) && isset($skills)) {
            $users = User::search($name)
                         ->join('skill_user', 'users.id', '=', 'skill_user.iduser')
                         ->whereIn('skill_user.idskill', $skills)
                         ->get();
        } elseif (isset($name) && isset($faculties)) {
            $users = User::search($name)
                         ->whereIn('users.idfaculty', $faculties)
                         ->get();
        } elseif (isset($name)) {
            $users = User::search($name)
                         ->get();
        } elseif (isset($faculties)) {
            $users = User::whereIn('users.idfaculty', $faculties)
                         ->get();
        } elseif (isset($skills)) {
            $users = User::join('skill_user', 'users.id', '=', 'skill_user.iduser')
                         ->whereIn('skill_user.idskill', $skills)
                         ->get();
        } else {
            $users = User::all();
        }

        return view('pages.searchMember', ['users' => $users]);
    }
}
