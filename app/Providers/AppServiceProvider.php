<?php

namespace App\Providers;

use App\ApiToken;
use App\Http\Livewire\Setup;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Sendportal\Base\Facades\Sendportal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        Sendportal::currentWorkspaceIdResolver(function() {
            if (auth()->user()) {
                return auth()->user()->currentWorkspaceId();
            }

            if ($apiToken = request()->bearerToken()) {
                return ApiToken::resolveWorkspaceId($apiToken);
            }
        });

        Sendportal::siderbarHtmlContentResolver(function() {
            return view('layouts.sidebar.manageUsersMenuItem')->render();
        });

        Sendportal::headerHtmlContentResolver(function() {
            return view('layouts.header.userManagementHeader')->render();
        });

        Livewire::component('setup', Setup::class);
    }
}
