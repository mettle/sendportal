<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Workspace;
use App\User;
use App\Repositories\WorkspacesRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class CreateWorkspace
{
    /** @var WorkspacesRepository */
    protected $workspaces;

    /** @var AddWorkspaceMember */
    protected $addWorkspaceMember;

    public function __construct(WorkspacesRepository $workspacesRepo, AddWorkspaceMember $addWorkspaceMember)
    {
        $this->workspaces = $workspacesRepo;
        $this->addWorkspaceMember = $addWorkspaceMember;
    }

    /**
     * Create a new workspace.
     *
     * @param User $user
     * @param string $workspaceName
     * @param string|null $role
     *
     * @return Workspace
     * @throws Exception
     */
    public function handle(User $user, string $workspaceName, ?string $role = null): Workspace
    {
        return DB::transaction(function () use ($user, $workspaceName, $role) {
            /** @var Workspace $workspace */
            $workspace = $this->workspaces->store([
                'name' => $workspaceName,
                'owner_id' => $user->id,
            ]);

            $this->addWorkspaceMember->handle($workspace, $user, $role);

            return $workspace;
        });
    }
}
