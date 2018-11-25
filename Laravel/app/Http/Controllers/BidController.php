<?php

namespace App\Http\Controllers;

use App\Bid;
use App\Skill;
use App\User;
use App\Team;
use App\Faculty;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Http\Controllers\ProposalController;



class BidController extends Controller
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

    public function show($id)
    {
        $bid = Bid::find($id);
        $bid->submissiondate = date('Y-m-d', strtotime($bid->submissiondate));

        $bid->team = Team::where('id', $bid->idteam)->get()->first();

        //members
        $facultys = [];
        $members = DB::select('SELECT * from users, team_member WHERE team_member.idteam = ? and team_member.iduser = users.id', [$bid->idteam]);

        foreach ($members as $member) {
            $aux = Faculty::where('id', $member->idfaculty)->get()->first();
            array_push($facultys, $aux);
        }
        $bid->facultysMembers = $facultys;

        //leader
        $leader = User::where('id', $bid->team->idleader)->get()->first();
        $facultyNameLeader = Faculty::where('id', $leader->idfaculty)->get()->first();
        $bid->facultyLeader= $facultyNameLeader;

        if (($key = array_search($facultyNameLeader, $facultys)) !== false) {
            unset($facultys[$key]);
        }

        $bid->facultysMembers = $facultys;

        //skills
        $skills = [];
       foreach ($members as $member) {
            $skillsMember = DB::select('SELECT * from skill_user WHERE iduser = ? ', [$member->id]);
            foreach ($skillsMember as $skill) {
                $skillName =  Skill::where('id', $skill->idskill)->get()->first();
                array_push($skills, $skillName);
            }
        }

        $skillsTeam = [];
        foreach ($skills as $skill) {
            array_push($skillsTeam, $skill->skillname);
        }

        $skillsTeam = array_unique($skillsTeam);


        $bid->skills = $skillsTeam;
        
        return view('pages.bid', [ 'bid' => $bid]);
    }

    public function setWinner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bidid' => 'required|integer|exists:bid,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $bid = Bid::find($request->input('bidid'));
        $bid->winner = true;
        $bid->save();

        $proposal = $bid->proposal;
        $proposal->proposal_status = "finished";
        $proposal->save();

        ProposalController::notifyBidders($bid->proposal->id);
        ProposalController::notifyWinner($bid->proposal->id);
        ProposalController::notifyOwner($bid->proposal->id);

        return redirect()->back()->withInput();
    }
}