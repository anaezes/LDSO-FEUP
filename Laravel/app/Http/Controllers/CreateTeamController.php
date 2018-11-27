<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use App\Team;

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
        $created_team = DB::transaction(function () use ($request) {
            $save_team = new Team;
            $save_team->idleader = Auth::user()->id;

            $save_team->teamname = $request->input('teamName');

            $save_team->save();

            $request->all();

            $save_team->members()->attach(Auth::user()->id);

            return $save_team;

            $members = $request->input('members');
            foreach ($members as $member) {
                $save_member = User::where('name', $member)->get()->first();
                $save_team->members()->attach($save_member->id);
            }
        });

        return $created_team;
    }

    /**
     * Creates a new team and redirects to its page.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createTeam(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        try {
            $created_team = $this->db_create($request);
        } catch (Exception $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem creating the team. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('create_team')
                ->withErrors($errors);
        }
        return redirect()->route('team', ['id' => $created_team->id]);
    }
}
