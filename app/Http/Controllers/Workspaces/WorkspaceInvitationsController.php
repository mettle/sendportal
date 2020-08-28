<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Workspaces;

use Exception;
use Illuminate\Http\RedirectResponse;
use Sendportal\Base\Http\Controllers\Controller;
use Sendportal\Base\Http\Middleware\OwnsCurrentWorkspace;
use Sendportal\Base\Http\Requests\Workspaces\WorkspaceInvitationStoreRequest;
use Sendportal\Base\Models\Invitation;
use Sendportal\Base\Services\Workspaces\SendInvitation;

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

        return redirect()->route('sendportal.users.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(Invitation $invitation): RedirectResponse
    {
        $invitation->delete();

        return redirect()->route('sendportal.users.index');
    }
}
