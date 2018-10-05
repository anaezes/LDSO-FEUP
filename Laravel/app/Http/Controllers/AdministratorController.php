<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\RequestedTermination;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdministratorController extends Controller
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
     *
     * Get all the account deletion requests from the database
     *
     */
    private function db_get_account_del_req()
    {
        $terminationRequests = RequestedTermination::all();
        return $terminationRequests;
    }

    /**
     *
     * Ignores a termination request from the database by deleting the tuple
     *
     */
    private function db_ignore_del_req($id)
    {
        RequestedTermination::find($id);
        $terminationRequest->delete;
    }

    /**
     *
     * Check if it's admin
     *
     */
    private function isNotAdmin()
    {
        if (Auth::user() == null || Auth::user()->users_status != "admin") {
            return true;
        }
    }

    /**
     *
     * Get all the account deletion requests
     *
     */
    private function getDeletionRequests()
    {
        return $this->db_get_account_del_req();
    }

    /**
     *isNotAdmin
     * Get all the account deletion requests
     *
     */
    private function ignoreDelRequest($id)
    {
        return $this->db_ignore_del_req($id);
    }

    /**
     * Shows the admin dashboard
     *
     * @return Response
     */
    public function show()
    {
        if ($this->isNotAdmin()) {
            return response('Forbidden.', 403);
        }

        $delRequests = $this->getDeletionRequests();
        return view('pages.admin', ['delRequests' => $delRequests]);
    }
}
