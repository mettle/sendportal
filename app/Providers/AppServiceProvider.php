<?php

declare(strict_types=1);

namespace App\Providers;

use App\Livewire\Setup;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use RuntimeException;
use Sendportal\Base\Facades\Sendportal;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrap();

        Sendportal::setCurrentWorkspaceIdResolver(
            static function () {
                /** @var User $user */
                $user = auth()->user();
                $request = request();
                $workspaceId = null;

                if ($user && $user->currentWorkspaceId()) {
                    $workspaceId = $user->currentWorkspaceId();
                } elseif ($request && (($apiToken = $request->bearerToken()) || ($apiToken = $request->get('api_token')))) {
                    $workspaceId = ApiToken::resolveWorkspaceId($apiToken);
                }

                if (! $workspaceId) {
                    throw new RuntimeException('Current Workspace ID Resolver must not return a null value.');
                }

                return $workspaceId;
            }
        );

        Sendportal::setSidebarHtmlContentResolver(
            static function () {
                return view('layouts.sidebar.manageUsersMenuItem')->render();
            }
        );



        Sendportal::setHeaderHtmlContentResolver(
            static function () {
                return view('layouts.header.userManagementHeader')->render();
            }
        );

        Paginator:: useBootstrap();

        Livewire::component('setup', Setup::class);
    }
}
