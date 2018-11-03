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
      * Gets the max bid of an proposal
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function getMaxBid(Request $request)
    {
        try {
            if (!($request->ajax() || $request->pjax())) {
                return response('Forbidden.', 403);
            }

            $proposalID = $request->input('proposalID');
         /*   $query = "SELECT max(bidValue) FROM bid WHERE idproposal = ?";
            $response = DB::select($query, [$proposalID]);
            if ($response[0]->max == null) {
            $response = [];
                $response[0]->max = 0.00;
            }*/
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return response()->json(0.00);
    }

    /**
      * Gets the max bid of an proposal (for internal use)
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public static function getMaxBidInternal($id)
    {
        /*$query = "SELECT max(bidValue) FROM bid WHERE idproposal = ?";
        $response = DB::select($query, [$id]);
        if ($response[0]->max == null) {
            $response[0]->max = 0.00;
        }*/

        return 0.00;
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

            $proposalID = $request->input('proposalID');
            $userID = Auth::user()->id;
            $bidValue = $request->input('value');

            $hasPayment = DB::select("SELECT paypalemail FROM users WHERE id = ?", [$userID]);
            if ($hasPayment[0]->paypalemail == null)
                return response()->json(['success' => false, 'message' => "You cannot bid without having a payment method attached to your account"]);

            $proposal = DB::select("SELECT proposal_status FROM proposal WHERE id = ?", [$proposalID]);
            if ($proposal[0]->proposal_status != "approved") {
                return response()->json(['success' => false, 'message' => "You cannot bid on an proposal that isn't going on."]);
            }

            $success = true;
            $info = "Your bid has been beaten.";

            $exists = DB::select("SELECT * FROM bid WHERE idBuyer = ? and idproposal = ?", [$userID, $proposalID]);
            if (sizeof($exists) > 0) {
                $lastbidder = DB::select('SELECT bid.idBuyer FROM bid WHERE idproposal = ? ORDER BY bidValue DESC', [$proposalID]);

                DB::update("UPDATE bid SET bidValue = ?, bidDate = now() WHERE idBuyer = ? AND idproposal = ?", [$bidValue, $userID, $proposalID]);
                $message = "Successfully updated your bid. You are now leading the proposal!";

                $notifID = DB::table('notification')->insertGetId(['information' => $info, 'idusers' => $lastbidder[0]->idbuyer]);
                DB::insert("INSERT INTO notification_proposal (idproposal, idNotification) VALUES (?, ?)", [$proposalID, $notifID]);

            } else {
                $lastbidder = DB::select('SELECT bid.idBuyer FROM bid WHERE idproposal = ? ORDER BY bidValue DESC', [$proposalID]);

                DB::insert("INSERT INTO bid (idBuyer, idproposal, bidValue) VALUES (?, ?, ?)", [$userID, $proposalID, $bidValue]);
                $message = "Successfully registered your bid. You are now leading the proposal!";

                $notifID = DB::table('notification')->insertGetId(['information' => $info, 'idusers' => $lastbidder[0]->idbuyer]);
                DB::insert("INSERT INTO notification_proposal (idproposal, idNotification) VALUES (?, ?)", [$proposalID, $notifID]);
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return response()->json(['success' => $success, 'message' => $message]);
    }
}
