<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Sendportal\Base\Facades\Sendportal;
use App\Http\Middleware\OwnsCurrentWorkspace;

Auth::routes(
    [
        'verify' => config('sendportal-host.auth.register', false),
        'register' => config('sendportal-host.auth.register', false),
        'reset' => config('sendportal-host.auth.password_reset'),
    ]
);

Route::get('setup', 'SetupController@index')->name('setup');

// Auth.
Route::middleware('auth')->namespace('Auth')->group(
    static function (Router $authRouter)
    {
        // Logout.
        $authRouter->get('logout', 'LoginController@logout')->name('logout');

        // Profile.
        $authRouter->middleware('verified')->name('profile.')->prefix('profile')->group(
            static function (
                Router $profileRouter
            ) {
                $profileRouter->get('/', 'ProfileController@show')->name('show');
                $profileRouter->get('/edit', 'ProfileController@edit')->name('edit');
                $profileRouter->put('/', 'ProfileController@update')->name('update');
            }
        );
    }
);

// Workspace User Management.
Route::namespace('Workspaces')
    ->middleware(['auth', 'verified', OwnsCurrentWorkspace::class])
    ->name('users.')
    ->prefix('users')
    ->group(
        static function (Router $workspacesRouter)
        {
            $workspacesRouter->get('/', 'WorkspaceUsersController@index')->name('index');
            $workspacesRouter->delete('{userId}', 'WorkspaceUsersController@destroy')->name('destroy');

            // Invitations.
            $workspacesRouter->name('invitations.')->prefix('invitations')
                ->group(
                    static function (Router $invitationsRouter)
                    {
                        $invitationsRouter->post('/', 'WorkspaceInvitationsController@store')->name('store');
                        $invitationsRouter->delete('{invitation}', 'WorkspaceInvitationsController@destroy')
                            ->name('destroy');
                    }
                );
        }
    );

// Workspace Management.
Route::namespace('Workspaces')->middleware(
    [
        'auth',
        'verified'
    ]
)->group(
    static function (Router $workspaceRouter)
    {
        $workspaceRouter->resource('workspaces', 'WorkspacesController')->except(
            [
                'create',
                'show',
                'destroy',
            ]
        );

        // Workspace Switching.
        $workspaceRouter->get('workspaces/{workspace}/switch', 'SwitchWorkspaceController@switch')
            ->name('workspaces.switch');

        // Invitations.
        $workspaceRouter->post('workspaces/invitations/{invitation}/accept', 'PendingInvitationController@accept')
            ->name('workspaces.invitations.accept');
        $workspaceRouter->post('workspaces/invitations/{invitation}/reject', 'PendingInvitationController@reject')
            ->name('workspaces.invitations.reject');
    }
);

Route::middleware(['auth', 'verified'])->group(
    static function ()
    {
        Sendportal::webRoutes();
    }
);

Sendportal::publicWebRoutes();
