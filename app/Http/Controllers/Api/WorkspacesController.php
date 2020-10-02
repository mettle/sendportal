<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Api;

use Illuminate\Http\Request;
use Sendportal\Base\Http\Controllers\Controller;
use Sendportal\Base\Http\Resources\Workspace as WorkspaceResource;
use Sendportal\Base\Repositories\WorkspacesRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WorkspacesController extends Controller
{
    /** @var WorkspacesRepository */
    private $workspaces;

    public function __construct(WorkspacesRepository $workspaces)
    {
        $this->workspaces = $workspaces;
    }

    /**
     * @throws Exception
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $workspaces = $this->workspaces->workspacesForUser($request->user());

        return WorkspaceResource::collection($workspaces);
    }
}
