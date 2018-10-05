<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordControllerCustom extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
      * sends an email to reset the password
      * @param Request $request
      * @return redirect to home
      */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = $this->broker()->sendResetLink(
            $request->only('email'), $this->resetNotifier()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return "A password reset link was sent to your e-mail. Don't forget to check the spam folder.";

            case Password::INVALID_USER:
            default:
                return redirect()->route('home')->withErrors("That user e-mail was not found.");
        }
    }

    /**
      * Resets the notifier
      */
    protected function resetNotifier()
    {
        return function($token)
        {
            new ResetPasswordNotification($token);
        };
    }

}
