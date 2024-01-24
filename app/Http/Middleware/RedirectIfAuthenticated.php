<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;


        foreach ($guards as $guard) {
            // dd($guard);
            if (Auth::guard($guard)->check()) {
                // Customize the redirection based on the user's role
                switch ($guard) {
                    case 'admin':
                        return redirect()->route('admin.index');
                        break;
                    default:
                        return redirect()->route('dashboard'); // Redirect regular users to the 'dashboard' route
                        break;
                }
            }
        }

        return $next($request);
    }
}
