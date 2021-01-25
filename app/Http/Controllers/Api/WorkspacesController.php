<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\WorkspacesRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\Workspace as WorkspaceResource;

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
