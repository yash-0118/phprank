<?php

namespace App\Exceptions;

use App\Exceptions\GroupNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => [trans('Admin Account logged out so first login as a admin ')],
            ])->redirectTo(route('login'));
        }
        if ($exception instanceof GroupNotFoundException || $exception instanceof ModelNotFoundException) {
            abort(404);
        }
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            
            Auth::guard('web')->logout();
            throw ValidationException::withMessages([
                'email' => [trans('You are not authorize for this Action')],
            ])->redirectTo(route('login'));
        }

        return parent::render($request, $exception);
    }
}
