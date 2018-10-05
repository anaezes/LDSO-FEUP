<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /**
      * Gets the max bid of an auction
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function getMaxBid(Request $request)
    {
        try {
            if (!($request->ajax() || $request->pjax())) {
                return response('Forbidden.', 403);
            }

            $auctionID = $request->input('auctionID');
            $query = "SELECT max(bidValue) FROM bid WHERE idAuction = ?";
            $response = DB::select($query, [$auctionID]);
            if ($response[0]->max == null) {
                $response[0]->max = 0.00;
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return response()->json($response[0]);
    }

    /**
      * Gets the max bid of an auction (for internal use)
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public static function getMaxBidInternal($id)
    {
        $query = "SELECT max(bidValue) FROM bid WHERE idAuction = ?";
        $response = DB::select($query, [$id]);
        if ($response[0]->max == null) {
            $response[0]->max = 0.00;
        }

        return $response[0]->max;
    }

    /**
      * Bids a new value
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function bidNewValue(Request $request)
    {
        try {
            if (!($request->ajax() || $request->pjax() || Auth::check())) {
                return response('Forbidden.', 403);
            }

            $auctionID = $request->input('auctionID');
            $userID = Auth::user()->id;
            $bidValue = $request->input('value');

            $hasPayment = DB::select("SELECT paypalemail FROM users WHERE id = ?", [$userID]);
            if ($hasPayment[0]->paypalemail == null)
                return response()->json(['success' => false, 'message' => "You cannot bid without having a payment method attached to your account"]);

            $auction = DB::select("SELECT auction_status FROM auction WHERE id = ?", [$auctionID]);
            if ($auction[0]->auction_status != "approved") {
                return response()->json(['success' => false, 'message' => "You cannot bid on an auction that isn't going on."]);
            }

            $success = true;
            $info = "Your bid has been beaten.";

            $exists = DB::select("SELECT * FROM bid WHERE idBuyer = ? and idAuction = ?", [$userID, $auctionID]);
            if (sizeof($exists) > 0) {
                $lastbidder = DB::select('SELECT bid.idBuyer FROM bid WHERE idAuction = ? ORDER BY bidValue DESC', [$auctionID]);

                DB::update("UPDATE bid SET bidValue = ?, bidDate = now() WHERE idBuyer = ? AND idAuction = ?", [$bidValue, $userID, $auctionID]);
                $message = "Successfully updated your bid. You are now leading the auction!";

                $notifID = DB::table('notification')->insertGetId(['information' => $info, 'idusers' => $lastbidder[0]->idbuyer]);
                DB::insert("INSERT INTO notification_auction (idAuction, idNotification) VALUES (?, ?)", [$auctionID, $notifID]);

            } else {
                $lastbidder = DB::select('SELECT bid.idBuyer FROM bid WHERE idAuction = ? ORDER BY bidValue DESC', [$auctionID]);

                DB::insert("INSERT INTO bid (idBuyer, idAuction, bidValue) VALUES (?, ?, ?)", [$userID, $auctionID, $bidValue]);
                $message = "Successfully registered your bid. You are now leading the auction!";

                $notifID = DB::table('notification')->insertGetId(['information' => $info, 'idusers' => $lastbidder[0]->idbuyer]);
                DB::insert("INSERT INTO notification_auction (idAuction, idNotification) VALUES (?, ?)", [$auctionID, $notifID]);
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return response()->json(['success' => $success, 'message' => $message]);
    }
}
