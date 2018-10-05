<?php

namespace App\Http\Controllers;

use App\Auction;
use App\AuctionModification;
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

        $auctions = Auction::where('auction_status', "waitingApproval")->get();
        $auction_modifications = AuctionModification::where('dateapproved', null)->get();
        $auction_modifications_ids = AuctionModification::where('dateapproved', null)->get()->pluck('idapprovedauction');
        $auctions_to_mod = Auction::whereIn('id', $auction_modifications_ids)->get();

        return view('pages.moderator', ['auctions' => $auctions, 'auction_modifications' => $auction_modifications, 'auctions_to_mod' => $auctions_to_mod]);
    }
}
