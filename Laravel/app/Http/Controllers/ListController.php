<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
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
      * Gets the user's proposals list page
      * @return page
      */
    public function myproposals()
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        $action = "MY_proposalS";

        return view('pages.list', ['action' => $action]);
    }

    /**
     * Gets the user's proposals list page
     * @return page
     */
    public function allproposals()
    {
        $action = "ALL_proposalS";

        return view('pages.list', ['action' => $action]);
    }

    /**
      * Gets the list with the proposals the user is in
      * @return page
      */
    public function proposalsImIn()
    {
        if (!Auth::check()) {
            return redirect('/home');
        }
        $action = "proposalS_IN";

        return view('pages.list', ['action' => $action]);
    }

     /**
      * Gets the list with the proposals the user won
      * @return page
      */
    public function proposalsIWon()
    {
        if (!Auth::check()) {
            return redirect('/home');
        }
        $action = "proposalsIWon";

        return view('pages.list', ['action' => $action]);
    }

    /**
      * Gets the proposal history of user
      * @return page
      */
    public function history()
    {
        if (!Auth::check()) {
            return redirect('/home');
        }
        $action = "history";

        return view('pages.list', ['action' => $action]);
    }

    /**
     * Gets the teams of the user
     * @return page
     */
    public function teams()
    {
        if (!Auth::check()) {
            return redirect('/home');
        }
        $action = "TEAMS";

        return view('pages.list', ['action' => $action]);
    }
}
