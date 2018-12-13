<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Team;
use App\User;
use App\Faculty;

class TeamController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Auth::user()->teams;
        return view('pages.list', ['teams' => $teams, 'action' => 'TEAMS']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'teamName' => 'string|required|max:100',
          'teamDescription' => 'string|required|max:200',
          'teamFaculty' => 'array|exists:faculty,id',
        ]);

        $team = new Team;
        $team->teamname = $request->input('teamName');
        $team->idleader = Auth::user()->id;
        $team->teamdescription = $request->input('teamDescription');
        $team->save();

        $team->members()->attach(Auth::user()->id);

        $faculties = $request->input('teamFaculty.*', []);
        if ($faculties == []) {
            $team->faculties()->syncWithoutDetaching($team->user->faculty->id);
        } else {
            $team->faculties()->syncWithoutDetaching($faculties);
        }

        return redirect()->route('team.show', $team->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = Team::find($id);
        return view('pages.team', ['team' => $team]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $validator = Validator::make($request->all(), [
          'teamname' => [
          'required',
          'string',
          'max:70',
            function ($attribute, $value, $fail) use ($team) {
                if (Team::where($attribute, $value)->exists() and $team->teamname != $value) {
                    $fail($value.' is already on use.');
                }
            },
          ],
          'teamdescription' => 'required|string|max:200',
          'teamfaculty' => 'array|exists:faculty,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator)
                         ->withInput();
        }

        $team->teamname = $request->input('teamname');
        $team->teamdescription = $request->input('teamdescription');
        $team->save();

        $faculties = $request->input('teamfaculty.*', []);
        foreach ($team->faculties as $faculty) {
            if (!in_array($faculty->id, $faculties)) {
                $team->faculties()->detach($faculty->id);
            }
        }
        if ($faculties == []) {
            $team->faculties()->syncWithoutDetaching($team->user->faculty->id);
        } else {
            $team->faculties()->syncWithoutDetaching($faculties);
        }

        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::destroy($id);

        return redirect()->route('team.index');
    }

    /**
     * Add member to the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addMember(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $validator = Validator::make($request->all(), [
          'username' => [
            'required',
            'string',
            'exists:users,username',
            function ($attribute, $value, $fail) use ($team) {
                if ($team->members()->where($attribute, $value)->exists()) {
                    $fail($value.' is already on the team.');
                }
            },
          ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $user = User::where('username', $request->input('username'))->first();
        $team->members()->attach($user->id);

        return back()->withInput();
    }

    /**
     * Remove member to the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeMember(Request $request, $id)
    {
        $team = Team::find($id);

        $validator = Validator::make($request->all(), [
          'memberId' => 'required|integer|exists:users,id|exists:team_member,iduser',
          'source' => 'required|string|in:leader,member',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $team->members()->detach($request->input('memberId'));

        if ($request->input('source') == 'member') {
            return redirect()->route('team.index');
        } else {
            return back()->withInput();
        }
    }
}
