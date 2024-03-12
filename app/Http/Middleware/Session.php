<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Session
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=Auth::user();
        if ($user) {
            $user_status = $user->is_active;
            if (!$user_status) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    'email' => [trans('Your account is disabled so contact admin for this.')],
                ])->redirectTo(route('login'));
            }
        }
       
        return $next($request);
    }
}
