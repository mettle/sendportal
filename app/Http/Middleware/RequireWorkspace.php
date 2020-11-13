<?php

namespace App\Http\Middleware;

use App\Workspace;
use Closure;
use Sendportal\Base\Facades\Sendportal;

class RequireWorkspace
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle($request, Closure $next)
    {
        $workspaceId = Sendportal::currentWorkspaceId();

        if (empty($workspaceId) && $request->is('api/*')) {
            return response('Unauthorized.', 403);
        }

        if (empty($workspaceId)) {
            abort(404);
        }

        return $next($request);
    }
}
