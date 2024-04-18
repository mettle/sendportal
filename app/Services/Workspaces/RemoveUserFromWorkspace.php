<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Models\User;
use App\Models\Workspace;

class RemoveUserFromWorkspace
{
    public function handle(User $user, Workspace $workspace): void
    {
        $workspace->users()->detach($user);

        $user->current_workspace_id = null;
        $user->save();
    }
}
