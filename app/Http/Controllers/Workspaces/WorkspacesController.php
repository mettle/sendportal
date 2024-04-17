<?php

declare(strict_types=1);

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use App\Http\Middleware\OwnsRequestedWorkspace;
use App\Http\Requests\Workspaces\WorkspaceStoreRequest;
use App\Http\Requests\Workspaces\WorkspaceUpdateRequest;
use App\Models\Workspace;
use App\Repositories\WorkspacesRepository;
use App\Services\Workspaces\CreateWorkspace;
use Exception;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkspacesController extends Controller
{
    /** @var WorkspacesRepository */
    protected $workspaces;

    /** @var CreateWorkspace */
    protected $createWorkspace;

    public function __construct(WorkspacesRepository $workspaces, CreateWorkspace $createWorkspace)
    {
        $this->workspaces = $workspaces;
        $this->createWorkspace = $createWorkspace;

        $this->middleware(OwnsRequestedWorkspace::class)->only([
            'edit',
            'update',
        ]);
    }

    public function index(Request $request): ViewContract
    {
        $user = $request->user()->load('workspaces', 'invitations.workspace');

        return view('workspaces.index', [
            'workspaces' => $user->workspaces,
            'invitations' => $user->invitations,
        ]);
    }

    /**
     * @throws Exception
     */
    public function store(WorkspaceStoreRequest $request): RedirectResponse
    {
        $this->createWorkspace->handle($request->user(), $request->get('name'), Workspace::ROLE_OWNER);

        return redirect()->route('workspaces.index');
    }

    public function edit(Workspace $workspace): ViewContract
    {
        return view('workspaces.edit', [
            'workspace' => $workspace,
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(WorkspaceUpdateRequest $request, Workspace $workspace): RedirectResponse
    {
        $this->workspaces->update($workspace->id, ['name' => $request->get('workspace_name')]);

        return redirect()->route('workspaces.index');
    }
}
