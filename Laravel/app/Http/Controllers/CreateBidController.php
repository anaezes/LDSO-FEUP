<?php

namespace App\Http\Controllers;

use Exception;
use App\Proposal;
use App\Bid;
use App\Team;
use App\Http\Controllers\Controller;
use App\Image;
use App\Language;
use App\Publisher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CreateBidController extends Controller
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
        if (!Auth::check()) {
            return redirect('/home');
        }

        return view('pages.createBid', ['id' => $id]);
    }

    /**
     * Creates a new bid
     * @param int $id
     * @param Request $request
     * @return created bid
     */
    private function db_create($id, Request $request)
    {
        $createdbid = DB::transaction(function () use ($id, $request) {

            $savebid = new Bid;

            $savebid->idproposal = $id;

            $savebid->idteam = $request->input('team');

            $savebid->description = $request->input('descriptionBid');

            $due = strtotime($request->input('submissionDate'));
            $savebid->submissiondate = date('Y-m-d H:i:s', $due);

            $savebid->save();

            return $savebid;
        });

        return $createdbid;
    }

    public function createBid(Request $request, $id)
    {

        if (!Auth::check()) {
            return redirect('/home');
        }

        try {
            $createdbid = $this->db_create($id, $request);
        } catch (Exception $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem creating the bid. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('createBid')
                ->withErrors($errors);
        }
        return redirect()->route('bid', ['id' => $createdbid->id]);
    }
}
