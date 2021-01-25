<?php

declare(strict_types=1);

namespace App\Http\Controllers\Workspaces;

use Exception;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Middleware\OwnsCurrentWorkspace;
use App\Http\Requests\Workspaces\WorkspaceInvitationStoreRequest;
use App\Models\Invitation;
use App\Services\Workspaces\SendInvitation;

class WorkspaceInvitationsController extends Controller
{
    /** @var SendInvitation */
    protected $sendInvitation;

    public function __construct(SendInvitation $sendInvitation)
    {
        $this->sendInvitation = $sendInvitation;

        $this->middleware(OwnsCurrentWorkspace::class)->only(['store']);
    }

    /**
     * @throws Exception
     */
    public function store(WorkspaceInvitationStoreRequest $request): RedirectResponse
    {
        $workspace = $request->user()->currentWorkspace();

        $this->sendInvitation->handle($workspace, $request->email);

        return redirect()->route('users.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(Invitation $invitation): RedirectResponse
    {
        $invitation->delete();

        return redirect()->route('users.index');
    }
}
