<?php

namespace App\Http\Controllers\Auth;


use Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    /**
      * gets a page with a reset password form
      * @param Request $request
      * @param String $token
      * @return redirect to page
      */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset_password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
      * gets a page to reset the password
      * @param String $token
      * @return page
      */
    public function getReset($token = null)
    {
        if (is_null($token))
        {
            throw new NotFoundHttpException;
        }

        // Change this to whatever you want ;)
        return view('auth.reset_password')->with('token', $token);
    }
}
