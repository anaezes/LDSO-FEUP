<?php

namespace App\Http\Controllers;

use Exception;
use App\Proposal;
use App\Skill;
use App\SkillProposal;
use App\Faculty;
use App\FacultyProposal;
use App\Http\Controllers\Controller;
use App\Image;
use App\Language;
use App\Publisher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CreateTeamController extends Controller
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

    public function show()
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        return view('pages.createTeam');
    }

    /**
      * Creates a new team
      * @param Request $request
      * @return created team
      */
    private function db_create(Request $request)
    {
            $createdteam = DB::transaction(function () use ($request) {
            $saveteam = new Proposal;
            $saveteam->idleader = Auth::user()->id;

            $saveteam->teamname = $request->input('name');


            $saveteam->save();



            $input = $request->all();



            return $saveteam;
        });

        return $createdteam;
    }

    /**
     * Creates a new team and redirects to its page.
     *
     */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        try {
            $createdteam = $this->db_create($request);
        } catch (Exception $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem creating the team. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('create_team')
                ->withErrors($errors);
        }
        return redirect()->route('team', ['id' => $createdteam->id]);
    }


}
