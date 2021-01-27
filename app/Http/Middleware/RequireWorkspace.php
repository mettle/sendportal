<?php

declare(strict_types=1);

namespace App\Http\Middleware;

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

        if ($workspaceId === null && $request->is('api/*')) {
            return response('Unauthorized.', 401);
        }

        if ($workspaceId === null) {
            abort(404);
        }

        return $next($request);
    }
}
