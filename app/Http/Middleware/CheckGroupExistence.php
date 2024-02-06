<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\GroupNotFoundException;

class CheckGroupExistence
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $group = $request->route('group');
        $splitGroup = explode("-", $group);
        if (count($splitGroup) > 1) {
            $group = $splitGroup[0] . ucfirst($splitGroup[1]);
        }

        if (!Setting::where('group', $group)->exists()) {
            throw new GroupNotFoundException;
        }

        return $next($request);
        
    }
}
