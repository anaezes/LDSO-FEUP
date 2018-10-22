<?php

namespace App\Http\Controllers;

use App\Proposal;
use App\ProposalModification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends Controller
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
      * Checks if the current user is not a moderator
      * @return true if it isn't
      */
    private function isNotModerator()
    {
        if (Auth::user() == null || Auth::user()->users_status != "moderator") {
            return true;
        }
    }

    /**
      * Gets the moderation page
      * @return page
      */
    public function show()
    {
        if ($this->isNotModerator()) {
            $erorIsnotAModerator = "You need to be a moderator to acess this page";
            return redirect('home')->withErrors($erorIsnotAModerator);
        }

        $proposals = Proposal::where('proposal_status', "waitingApproval")->get();
        $proposal_modifications = ProposalModification::where('dateapproved', null)->get();
        $proposal_modifications_ids = ProposalModification::where('dateapproved', null)->get()->pluck('idapprovedproposal');
        $proposals_to_mod = Proposal::whereIn('id', $proposal_modifications_ids)->get();

        return view('pages.moderator', ['proposals' => $proposals, 'proposal_modifications' => $proposal_modifications, 'proposals_to_mod' => $proposals_to_mod]);
    }
}
