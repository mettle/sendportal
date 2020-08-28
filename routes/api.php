<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Sendportal\Base\Facades\Sendportal;

Route::middleware([
    'auth:api',
    config('sendportal.throttle_middleware'),
])->name('sendportal.api.')->namespace('Api')->group(static function (Router $router) {
    $router->apiResource('workspaces', 'WorkspacesController')->only('index');
});

Route::middleware([
    'auth:api',
    config('sendportal.throttle_middleware'),
    \App\Http\Middleware\VerifyUserOnWorkspace::class
])->group(function() {

    Sendportal::apiRoutes();

});

Sendportal::publicApiRoutes();