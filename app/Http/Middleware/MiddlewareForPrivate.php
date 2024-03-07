<?php

namespace App\Http\Middleware;

use App\Models\SiteMaster;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareForPrivate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth()->id();
        $id = $request->route('id');
        $site = SiteMaster::findOrFail($id);
        $userId = $site->user_id;
        $visibility = $site->visibility;
        if (!(auth()->check())) {
            if ($visibility === 'public') {
                return redirect()->route('public.report', ['id' => $id]);
            } elseif ($visibility === 'private') {
                abort(403, "this Report is private");
            } elseif ($visibility === "password") {
                return redirect()->route('password.entry.form', ['id' => $id]);
            } else {
                abort(403, "This Report is private");
            }
        } else {
            if ($visibility === 'private' && auth()->check() && $user == $userId) {
                return $next($request);
            } elseif ($visibility == 'public') {
                if ($user == $userId) {
                    return $next($request);
                }
                return redirect()->route('public.report', ['id' => $id]);
            } elseif ($visibility == 'password') {
                if ($user == $userId) {
                    return $next($request);
                }
                return redirect()->route('password.entry.form', ['id' => $id]);
            } else {
                abort(403, "This Report is private");
            }
            return $next($request);
        }
        return $next($request);
    }
}
