<?php

namespace App\Http\Middleware;

use Closure;

class OwnsCurrentWorkspace
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure $next
     * @return string
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->ownsCurrentWorkspace()) {
            abort(404);
        }

        return $next($request);
    }
}
