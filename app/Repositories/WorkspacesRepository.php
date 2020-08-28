<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Workspace;
use App\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Sendportal\Base\Repositories\BaseEloquentRepository;

class WorkspacesRepository extends BaseEloquentRepository
{
    /** @var string */
    protected $modelName = Workspace::class;

    /**
     * Get a paginated list of all the workspaces a user is a part of.
     *
     * @throws Exception
     */
    public function workspacesForUser(User $user): LengthAwarePaginator
    {
        return $this->getQueryBuilder()
            ->select('workspaces.*')
            ->leftJoin('workspace_users', 'workspace_users.workspace_id', '=', 'workspaces.id')
            ->where('workspace_users.user_id', $user->id)
            ->paginate(25);
    }
}
