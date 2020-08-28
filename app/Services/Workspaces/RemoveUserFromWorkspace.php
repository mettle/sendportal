<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Workspace;
use App\User;

class RemoveUserFromWorkspace
{
    public function handle(User $user, Workspace $workspace): void
    {
        $workspace->users()->detach($user);

        $user->current_workspace_id = null;
        $user->save();
    }
}
