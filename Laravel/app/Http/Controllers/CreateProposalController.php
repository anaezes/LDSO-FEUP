<?php

namespace App\Http\Controllers;

use App\Proposal;
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
            $saveproposal->idseller = Auth::user()->id;

            $savePublisher = Publisher::where('publishername', $request->input('publisher'))->get()->first();

            if ($savePublisher == null) {
                $savePublisher = new Publisher;
                $savePublisher->publishername = $request->input('publisher');
                $savePublisher->save();
                $savePublisher = $savePublisher->id;
            } else {
                $savePublisher = $savePublisher->id;
            }

            $savefaculty = Faculty::where('facultyname', $request->input('faculty'))->get()->first();

            $saveproposal->idpublisher = $savePublisher;
            $saveproposal->idlanguage = Language::where('languagename', $request->input('language'))->get()->first()->id;

            $saveproposal->title = $request->input('title');
            $saveproposal->author = $request->input('author');
            $saveproposal->description = $request->input('description');
            $saveproposal->isbn = $request->input('isbn');
            $saveproposal->duration = $this->buildDuration($request);

            $saveproposal->save();

            if ($savefaculty != null) {
                $savefacultyproposal->idfaculty = $savefaculty->id;
                $savefacultyproposal->idproposal = $saveproposal->id;
                $savefacultyproposal->save();
            }

            $input = $request->all();
            $images = array();
            if ($files = $request->file('images')) {
                $integer = 0;
                foreach ($files as $file) {
                    $name = time() . (string) $integer . $file->getClientOriginalName();
                    $file->move('img', $name);
                    $images[] = $name;
                    $integer += 1;
                }
            }

            foreach ($images as $image) {
                $saveImage = new Image;
                $saveImage->source = $image;
                $saveImage->idproposal = $saveproposal->id;
                $saveImage->save();
            }

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
