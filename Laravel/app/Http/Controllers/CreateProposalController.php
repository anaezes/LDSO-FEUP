<?php

namespace App\Http\Controllers;

use App\Proposal;
use App\Faculty;
use App\FacultyProposal;
use App\Http\Controllers\Controller;
use App\Image;
use App\Language;
use App\Publisher;
use App\SkillProposal;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CreateproposalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

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

        return view('pages.create');
    }

    /**
      * Creates a new proposal
      * @param Request $request
      * @return created proposal
      */
    private function db_create(Request $request)
    {
        $createdproposal = DB::transaction(function () use ($request) {
            $saveproposal = new Proposal;
            $savefacultyproposal = new FacultyProposal;
            $saveskillproposal = new SkillProposal;
            $saveproposal->idProponent = Auth::user()->id;


            $savefaculty = Faculty::where('facultyName', $request->input('faculty'))->get()->first();


            $saveskill = Skill::where('skillName', $request->input('skill'))->get()->first()->id;

            $saveproposal->title = $request->input('title');
            $saveproposal->description = $request->input('description');
            $saveproposal->duration = $this->buildDuration($request);

            $saveproposal->save();

            if ($savefaculty != null) {
                $savefacultyproposal->idFaculty = $savefaculty->id;
                $savefacultyproposal->idProposal = $saveproposal->id;
                $savefacultyproposal->save();
            }

            if($saveskill != null){
                $saveskillproposal->idSkill = $saveskill->id;
                $saveskillproposal->idProposal = $saveproposal->id;
            }

            $input = $request->all();



            return $saveproposal;
        });

        return $createdproposal;
    }

    /**
     * Creates a new proposal and redirects to it's page.
     *
     */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        try {
            $createdproposal = $this->db_create($request);
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem creating the proposal. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('create')
                ->withErrors($errors);
        }
        return redirect()->route('proposal', ['id' => $createdproposal->id]);
    }

    private function buildDuration(Request $request)
    {
        $days = $request->input('days');
        $hours = $request->input('hours');
        $minutes = $request->input('minutes');

        $totalSeconds = $days * 86400 + $hours * 3600 + $minutes * 60;
        return $totalSeconds;
    }
}
