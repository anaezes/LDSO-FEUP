<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProposalController;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use App\Faculty;
use App\Skill;
use App\Users;

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
      * gets the advanced search page
      * @return page
      */
    public function show()
    {
        $proposals = [];
        $responseSentence = "Use the advanced search options above to find proposals";

        return view('pages.search', ['proposals' => $proposals, 'responseSentence' => $responseSentence]);
    }

    public function simpleSearchMember(Request $request)
    {

        $input = $request->all();
        if (isset($input['faculty']))
            $faculties = $input['faculty'];
        else
            $faculties = null;

        if(isset($input['skill']))
            $skills = $input['skill'];
        else
            $skills = null;

        $ids = [];

        try {
            if ($faculties != null && $skills !== null)
            {
                foreach ($faculties as $faculty)
                {
                    $f = Faculty::where('facultyname', $faculty)->get()->first();
                    foreach ($skills as $skill)
                    {
                        $s = Skill::where('skillname', $skill)->get()->first();
                        $res = DB::select("SELECT users.id, skill_user.iduser FROM users, skill_user WHERE idfaculty = ? AND skill_user.idskill = ? AND users.id = skill_user.iduser", [$f->id, $s->id]);
                        foreach ($res as $entry)
                        {
                            array_push($ids, $entry->id);
                        }
                    }
                }
            }
            elseif ($faculties !== null)
            {
                foreach ($faculties as $faculty)
                {
                    $f = Faculty::where('facultyname', $faculty)->get()->first();
                    $res = DB::select("SELECT users.id FROM users WHERE idfaculty = ?", [$f->id]);
                    foreach ($res as $entry)
                    {
                        array_push($ids, $entry->id);
                    }
                }
            } elseif ($skills !== null)
            {
                foreach ($skills as $skill)
                {
                    $s = Skill::where('skillname', $skill)->get()->first();
                    $res = DB::select("SELECT users.id, skill_user.iduser FROM users, skill_user WHERE skill_user.idskill = ? AND users.id = skill_user.iduser", [$s->id]);
                    foreach ($res as $entry)
                    {
                        array_push($ids, $entry->id);
                    }
                }
            }
            else
                {
                $res = DB::select("SELECT users.id FROM users");
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }
            }

            if (sizeof($ids) == 0)
            {
                return view('pages.searchMember', ['members' => []]);
            }

            $parameters = implode(",", $ids);

            $query = "SELECT id, username FROM users WHERE users.id IN (" . $parameters . ")";
            $members = DB::select($query, []);

        } catch (QueryException $qe)
        {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem searching for members. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('searchMember')
                ->withErrors($errors);
        }

        return view('pages.searchMember', ['members' => $members]);
    }
    /**
      * does a simpel search request
      * @param Request $request
      * @return page with results
      */
    public function simpleSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'searchTerm' => 'nullable|string',
            'proposal' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('home')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $searchTerm = $input['searchTerm'];
        $proposal = $input['proposal'];
        $approved = "approved";
        $responseSentence = [];
        $ids = [];

        try {
            if ($searchTerm != null) {
                $res = DB::select("SELECT proposal.id FROM proposal WHERE title @@ plainto_tsquery('english',?) and proposal_status = ?", [$searchTerm, $approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, ' with title "' . $searchTerm . '"');
            }
            if ($proposal !== 'All') {
                $res = DB::select('SELECT proposal.id FROM proposal, proposal_proposal, proposal WHERE proposal_proposal.idproposal = proposal.id and proposal_proposal.idproposal = proposal.id and proposalName = ? and proposal_status = ?', [$proposal, $approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, 'in proposal ' . $proposal);
            } else {
                $res = DB::select("SELECT id FROM proposal WHERE proposal_status = ?", [$approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, 'in any proposal');
            }

            if (sizeof($ids) == 0) {
                return view('pages.search', ['proposals' => [], 'responseSentence' => "No results were found"]);
            }
            $parameters = implode(",", $ids);



            $query = "SELECT proposal.id, title, duration, dateApproved FROM proposal WHERE proposal.id IN (" . $parameters . ")";
            $proposals = DB::select($query, []);

            $this->buildTimestamps($proposals);
            $this->getMaxBids($proposals);

            $responseSentence = implode(' and ', $responseSentence);
            $responseSentence = 'Your search results for proposals ' . $responseSentence . ':';
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem searching for proposals. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('search')
                ->withErrors($errors);
        }

        return view('pages.search', ['proposals' => $proposals, 'responseSentence' => $responseSentence]);
    }

    /**
      * Builds all timestamps for an array of proposals
      * @param $proposals
      */
    private function buildTimestamps($proposals)
    {
        foreach ($proposals as $proposal) {
            $ts = ProposalController::createTimestamp($proposal->datecreated, $proposal->duration);
            $proposal->timestamp = $ts;
        }
    }

    /**
      * sets the max bid on an array of proposals
      * @param $proposals
      */
    private function getMaxBids($proposals)
    {
        foreach ($proposals as $proposal) {
            /*$res = DB::select("SELECT max(bidValue) FROM bid WHERE idproposal = ?", [$proposal->id]);
            if ($res[0]->max == null) {*/
                //$proposal->bidValue = "No bids yet";
           /* } else {
                $proposal->bidValue = $res[0]->max . "€";
            }*/
        }
    }
}
