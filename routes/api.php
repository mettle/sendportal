<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Sendportal\Base\Facades\Sendportal;

Route::middleware([
    'auth:api',
    config('sendportal-host.throttle_middleware'),
])->name('sendportal.api.')->namespace('Api')->group(static function (Router $router) {
    $router->apiResource('workspaces', 'WorkspacesController')->only('index');
});

Route::middleware([
    config('sendportal-host.throttle_middleware'),
    \App\Http\Middleware\RequireWorkspace::class
])->group(function() {

    Sendportal::apiRoutes();

});

Sendportal::publicApiRoutes();