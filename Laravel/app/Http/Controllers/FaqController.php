<?php

namespace App\Http\Controllers;

use App\Proposal;
use App\Http\Controllers\Controller;

class FaqController extends Controller
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
     * Shows the auction for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        return view('pages.faq');
    }
}
