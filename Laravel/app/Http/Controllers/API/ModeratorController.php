<?php

namespace App\Http\Controllers\API;

use App\Proposal;
use App\ProposalModification;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModeratorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private function isNotModerator()
    {
        if (Auth::user() == null || Auth::user()->users_status != "moderator") {
            return true;
        }
    }

    /**
      * Removes an auction
      * @param int $id
      */
    private function db_remove_auction($id)
    {
        DB::table('auction')
            ->where('id', $id)
            ->update(['auction_status' => 'removed', 'dateremoved' => 'now()']);
    }

    /**
      * Approves an auction
      * @param int $id
      */
    private function db_restore_auction($id)
    {
        DB::table('auction')
            ->where('id', $id)
            ->update(['auction_status' => 'approved', 'dateremoved' => null]);
    }

    /**
      * Approves an auction
      * @param int $id
      */
    private function db_approve_creation($id)
    {
        DB::table('auction')
            ->where('id', $id)
            ->update(['auction_status' => 'approved', 'dateapproved' => 'now()']);
    }

    /**
      * Reproves an auction
      * @param int $id
      */
    private function db_remove_creation($id)
    {
        DB::table('auction')
            ->where('id', $id)
            ->update(['auction_status' => 'removed', 'dateapproved' => 'now()']);
    }

    /**
      * Approves a modification
      * @param int $ida
      * @param int $idm
      */
    private function db_approve_modification($ida, $idm)
    {
        DB::transaction(function () use ($ida, $idm) {
            $auction = Proposal::find($ida);
            $auction_modified = ProposalModification::find($idm);

            $auction_modified->dateapproved = DB::raw('now()');
            $auction_modified->is_approved = true;
            $auction_modified->save();

            $auction->description = $auction_modified->newdescription;
            $auction->save();
        });
    }

    /**
      * Promotes someone to a moderator
      * @param Request $request
      */
    private function db_remove_modification($idm)
    {
        $auction_modified = ProposalModification::find($idm);

        $auction_modified->dateapproved = DB::raw('now()');
        $auction_modified->is_approved = false;
        $auction_modified->save();
    }

    /**
      * Gets the new description of an auction
      * @param int $ida
      * @param int $idm
      * @return JSON
      */
    private function db_get_new_description($ida, $idm)
    {
        $auction = Proposal::find($ida);

        $description = array(
            "new" => ProposalModification::find($idm)->newdescription,
            "old" => $auction->description,
            "title" => $auction->title,
        );

        $auction_modified = ProposalModification::find($idm);
        $newdescription = $auction_modified->newdescription;
        return json_encode($description);
    }

    /**
      * Approves the creation of an auction
      * @param Request request
      * @return JSON
      */
    private function approve_creation($request)
    {
        $this->db_approve_creation($request->ida);
        return response()->json(['success' => 'Proposal creation was successfully approved.', 'action' => $request->action, 'requestId' => $request->ida, 'did' => '1']);
    }

    /**
      * Approves the removal of an auction
      * @param Request request
      * @return JSON
      */
    private function remove_creation($request)
    {
        $this->db_remove_creation($request->ida);
        return response()->json(['success' => 'Proposal creation was successfully removed.', 'action' => $request->action, 'requestId' => $request->ida, 'did' => '2']);
    }

    /**
      * Approves themodification of an auction
      * @param Request request
      * @return JSON
      */
    private function approve_modification($request)
    {
        $this->db_approve_modification($request->ida, $request->idm);
        return response()->json(['success' => 'Proposal modification was successfully approved.', 'action' => $request->action, 'requestIdModification' => $request->idm, 'did' => '3']);
    }

    /**
      * Denies the modification of an auction
      * @param Request request
      * @return JSON
      */
    private function remove_modification($request)
    {
        $this->db_remove_modification($request->idm);
        return response()->json(['success' => 'Proposal modification was successfully removed.', 'action' => $request->action, 'requestIdModification' => $request->idm, 'did' => '4']);
    }

    /**
      * Approves an auction
      * @param Request request
      * @return JSON
      */
    private function restore_auction($request)
    {
        $this->db_restore_auction($request->ida);
        return response()->json(['success' => 'Proposal was successfully restored.', 'action' => $request->action, 'requestIdModification' => $request->idm, 'did' => '6']);
    }

    /**
      * Removes an auction
      * @param Request request
      * @return JSON
      */
    private function remove_auction($request)
    {
        $this->db_remove_auction($request->ida);
        return response()->json(['success' => 'Proposal was successfully removed.', 'action' => $request->action, 'requestIdModification' => $request->idm, 'did' => '5']);
    }

    /**
      * Gets the new description of an auction
      * @param Request request
      * @return JSON
      */
    private function get_new_description($request)
    {
        return $this->db_get_new_description($request->ida, $request->idm);
    }

    /**
      * Handles an unknown request
      * @param Request request
      * @return JSON
      */
    private function unkown_action($request)
    {
        return response()->json(['unexpected' => 'Error: unknown action stated in request.', 'action' => $request->action]);
    }

    /**
      * Does a mod action based on input
      * @param Request request
      * @return JSON
      */
    public function action(Request $request)
    {
        try {
            if ($this->isNotModerator()) {
                return redirect('home');
            }

            $action = $request->action;

            if ($action === "approve_creation") {
                return $this->approve_creation($request);
            }

            if ($action === "remove_creation") {
                return $this->remove_creation($request);
            }

            if ($action === "approve_modification") {
                return $this->approve_modification($request);
            }

            if ($action === "remove_modification") {
                return $this->remove_modification($request);
            }

            if ($action === "restore_auction") {
                return $this->restore_auction($request);
            }

            if ($action === "remove_auction") {
                return $this->remove_auction($request);
            }

            if ($action === "get_new_description") {
                return $this->get_new_description($request);
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return $this->unkown_action($request);
    }
}
