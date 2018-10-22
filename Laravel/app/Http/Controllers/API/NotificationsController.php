<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
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
      * Gets all notifications of current user
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function getNotifications(Request $request)
    {
        try {
            if (!($request->ajax() || $request->pjax()) || !Auth::check()) {
                return response('Forbidden.', 403);
            }

            $response = DB::select('SELECT notification.id, notification.information,notification.dateSent, notification_proposal.idproposal, proposal.title
                                FROM notification, notification_proposal, proposal
                                WHERE notification.is_seen=FALSE
                                AND notification.idusers=?
                                AND notification_proposal.idNotification=notification.id
                                AND notification_proposal.idproposal=proposal.id', [Auth::user()->id]);
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }
        return response()->json($response);
    }

    /**
      * Marks a notification as seen
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function markAsSeen(Request $request)
    {
        try {
            if (!($request->ajax() || $request->pjax()) || !Auth::check()) {
                return response('Forbidden.', 403);
            }

            $id = $request->input('notification_id');
            if ($id !== null) {
                try {
                    DB::update("UPDATE notification SET is_seen = TRUE WHERE idusers = ? AND id=?", [Auth::user()->id, $id]);
                } catch (QueryException $qe) {
                    $this->warn($qe);
                    return response('NOT FOUND', 404);
                }
            } else {
                return response('Incorrect Request', 400);
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return response('OK', 200);
    }
}
