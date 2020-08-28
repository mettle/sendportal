<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sendportal::currentWorkspaceIdResolver(function() {
            return auth()->user()->currentWorkspaceId();
        });

        Sendportal::siderbarHtmlContentResolver(function() {
            return view('layouts.sidebar.manageUsersMenuItem')->render();
        });

        Sendportal::headerHtmlContentResolver(function() {
            return view('layouts.header.userManagementHeader')->render();
        });
    }
}
