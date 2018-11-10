<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $errors = new MessageBag();

        // add your error messages:
        $errors->add('An unexpected error ocurred', $exception->getMessage());

        //render for dev env
        if (Auth::check()) {
            $id = Auth::user()->id;
        } else {
            $id = "guest";
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return parent::render($request, $exception);
        }

        $logMessage = "\nID: " . $id . "\n" . $exception->getMessage() . "\n" . $exception->getTraceAsString() . "\n\n";
        Log::error($logMessage);

        return redirect()
            ->route('home')
            ->withErrors($errors);

    }
}
