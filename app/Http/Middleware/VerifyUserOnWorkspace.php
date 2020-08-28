<?php

namespace App\Http\Middleware;

use Sendportal\Base\Models\Workspace;
use Closure;

class VerifyUserOnWorkspace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $workspace = Workspace::find($request->route()->parameter('workspaceId'));

        if (! $workspace) {
            abort(403, 'Unauthorized');
        }

        abort_unless($request->user()->onWorkspace($workspace), 403, 'Unauthorized');

        config()->set('current_workspace_id', $workspace->id);

        return $next($request);
    }
}
