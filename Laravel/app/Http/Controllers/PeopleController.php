<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(30);

        return view('pages.people', ['users' => $users]);
    }
}
