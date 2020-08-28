<?php

declare(strict_types=1);

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\User;
use App\Services\Workspaces\RemoveUserFromWorkspace;

class WorkspaceUsersController extends Controller
{
    /** @var RemoveUserFromWorkspace */
    private $removeUserFromWorkspace;

    public function __construct(RemoveUserFromWorkspace $removeUserFromWorkspace)
    {
        $this->removeUserFromWorkspace = $removeUserFromWorkspace;
    }

    public function index(Request $request): ViewContract
    {
        return view('users.index', [
            'users' => $request->user()->currentWorkspace->users,
            'invitations' => $request->user()->currentWorkspace->invitations,
        ]);
    }

    /**
     * Remove a user from the current workspace.
     *
     * @param int $userId
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, int $userId): RedirectResponse
    {
        /* @var $requestUser \App\Models\User */
        $requestUser = $request->user();

        if ($userId === $requestUser->id) {
            return redirect()
                ->back()
                ->with('error', __('You cannot remove yourself from your own workspace.'));
        }

        $workspace = $requestUser->currentWorkspace();

        $user = User::find($userId);

        $this->removeUserFromWorkspace->handle($user, $workspace);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                __(':user was removed from :workspace.', ['user' => $user->name, 'workspace' => $workspace->name])
            );
    }
}
