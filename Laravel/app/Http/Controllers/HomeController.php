<?php

namespace App\Http\Controllers;

use App\Proposal;
use App\Http\Controllers\Controller;

class HomeController extends Controller
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
      * Gets the home page
      * @return page
      */
    public function show()
    {
        $proposals = Proposal::all();
        return view('pages.home', ['proposals' => $proposals]);
    }
}
