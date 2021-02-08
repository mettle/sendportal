<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Models\Invitation;
use App\Models\Workspace;
use App\Models\User;
use Exception;
use RuntimeException;

class AcceptInvitation
{
    /** @var AddWorkspaceMember */
    protected $addWorkspaceMember;

    public function __construct(AddWorkspaceMember $addWorkspaceMember)
    {
        $this->addWorkspaceMember = $addWorkspaceMember;
    }

    /**
     * Accept user invitation.
     *
     * @param User $user
     * @param Invitation $invitation
     *
     * @return bool
     * @throws Exception
     */
    public function handle(User $user, Invitation $invitation): bool
    {
        $workspace = $this->resolveWorkspace($invitation->workspace_id);

        if (!$workspace) {
            throw new RuntimeException("Invalid workspace ID encountered: {$invitation->workspace_id}");
        }

        $this->addWorkspaceMember->handle($workspace, $user, Workspace::ROLE_MEMBER);

        $invitation->delete();

        return true;
    }

    protected function resolveWorkspace(int $workspaceId): ?Workspace
    {
        return Workspace::where('id', $workspaceId)->first();
    }
}
