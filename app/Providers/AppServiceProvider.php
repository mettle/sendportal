<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\ApiToken;
use App\Http\Livewire\Setup;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Sendportal\Base\Facades\Sendportal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Sendportal::setCurrentWorkspaceIdResolver(
            static function ()
            {
                /** @var User $user */
                if ($user = auth()->user()) {
                    return $user->currentWorkspaceId();
                }

                if (($request = request()) && $apiToken = $request->bearerToken()) {
                    return ApiToken::resolveWorkspaceId($apiToken);
                }

                return null;
            }
        );

        Sendportal::setSidebarHtmlContentResolver(
            static function ()
            {
                return view('layouts.sidebar.manageUsersMenuItem')->render();
            }
        );

        Sendportal::setHeaderHtmlContentResolver(
            static function ()
            {
                return view('layouts.header.userManagementHeader')->render();
            }
        );

        Livewire::component('setup', Setup::class);
    }
}
