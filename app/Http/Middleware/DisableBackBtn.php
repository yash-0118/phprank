<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableBackBtn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if ($request->routeIs('login')) {
        //     return redirect('/dashboard');
        // }
        
        $response = $next($request);
        $response->headers->set('Cache-Control', 'nocache,no-store,max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sat,01 Jan 2000 00:00:00 GMT');
        // header('Location:http://127.0.0.1:8000/dashboard');
        return $response;
        // return  $next($request);
    }
}
