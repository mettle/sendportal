<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Models\Workspace;
use App\Models\User;

class AddWorkspaceMember
{
    /**
     * Attach a user to a workspace.
     *
     * @param Workspace $workspace
     * @param User $user
     * @param string|null $role
     */
    public function handle(Workspace $workspace, User $user, ?string $role = null): void
    {
        if (!$user->onWorkspace($workspace)) {
            $workspace->users()->attach($user, ['role' => $role ?: Workspace::ROLE_MEMBER]);
        }
    }
}
