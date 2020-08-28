<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Workspaces;

use Exception;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sendportal\Base\Http\Controllers\Controller;
use App\Http\Middleware\OwnsRequestedWorkspace;
use Sendportal\Base\Http\Requests\Workspaces\WorkspaceStoreRequest;
use Sendportal\Base\Http\Requests\Workspaces\WorkspaceUpdateRequest;
use Sendportal\Base\Models\Workspace;
use Sendportal\Base\Repositories\WorkspacesRepository;
use Sendportal\Base\Services\Workspaces\CreateWorkspace;

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
            'update'
        ]);
    }

    public function index(Request $request): ViewContract
    {
        $user = $request->user()->load('workspaces', 'invitations.workspace');

        return view('sendportal::workspaces.index', [
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

        return redirect()->route('sendportal.workspaces.index');
    }

    public function edit(Workspace $workspace): ViewContract
    {
        return view('sendportal::workspaces.edit', [
            'workspace' => $workspace
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(WorkspaceUpdateRequest $request, Workspace $workspace)
    {
        $this->workspaces->update($workspace->id, ['name' => $request->get('workspace_name')]);

        return redirect()->route('sendportal.workspaces.index');
    }
}
