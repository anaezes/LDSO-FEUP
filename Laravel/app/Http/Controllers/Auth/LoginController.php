<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    /**
      * gets the home page based on the kind of user
      * @param Request $request
      * @param User $user
      * @return redirect to page
      */
    protected function authenticated(Request $request, User $user)
    {
        if ($user->isNormal()) {
            return redirect()->route('home');
        } elseif ($user->isModerator() || $user->isAdmin()) {
            return redirect()->route('home');
        } elseif ($user->isSuspended()) {
            Auth::logout();
            return redirect()->route('contact')->withErrors("Your account has been suspended. Contact the admin through the contact page for details.");
        } elseif ($user->isBanned()) {
            Auth::logout();
            return redirect()->route('contact')->withErrors("You are permanently banned. Contact the admin through the contact page for details.");
        } elseif ($user->isTerminated()) {
            Auth::logout();
            return redirect()->route('contact')->withErrors("Your account has been terminated. Contact the admin through the contact page for details.");
        }
    }
}
