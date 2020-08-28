<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnsRequestedWorkspace
{
    public function handle(Request $request, Closure $next)
    {
        if (!$user = $request->user()) {
            abort(404);
        }

        $workspace = $request->workspace ?? $request->workspace;

        if (!$workspace) {
            abort(404);
        }

        if (!$user->ownsWorkspace($workspace)) {
            abort(404);
        }

        return $next($request);
    }
}
