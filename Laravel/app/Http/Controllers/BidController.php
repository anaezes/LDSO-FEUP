<?php


namespace App\Http\Controllers;
use App\Bid;


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

    public function show($id)
    {
        $bid = Bid::find($id);

        //todo

        return view('pages.bid', ['bid' => $bid]);
    }

}