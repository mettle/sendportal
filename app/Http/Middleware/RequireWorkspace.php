<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use RuntimeException;
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
        try {
            Sendportal::currentWorkspaceId();
        }
        catch(RuntimeException $exception) {
            if($request->is('api/*')) {
                return response('Unauthorized.', 401);
            }

            abort(404);
        }

        return $next($request);
    }
}
