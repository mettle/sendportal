<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Models\User;
use App\Models\Workspace;

class AddWorkspaceMember
{
    /**
     * Attach a user to a workspace.
     */
    public function handle(Workspace $workspace, User $user, ?string $role = null): void
    {
        if (! $user->onWorkspace($workspace)) {
            $workspace->users()->attach($user, ['role' => $role ?: Workspace::ROLE_MEMBER]);
        }
    }
}
