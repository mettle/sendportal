<?php

declare(strict_types=1);

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use App\Invitation;
use App\Services\Workspaces\AcceptInvitation;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PendingInvitationController extends Controller
{
    /** @var AcceptInvitation */
    protected $acceptInvitation;

    public function __construct(AcceptInvitation $acceptInvitation)
    {
        $this->acceptInvitation = $acceptInvitation;
    }

    /**
     * @throws Exception
     */
    public function accept(Request $request, Invitation $invitation): RedirectResponse
    {
        abort_unless($request->user()->id === $invitation->user_id, 404);

        if ($invitation->isExpired()) {
            return redirect()->back()->with('error', __('The invitation is no longer valid.'));
        }

        $this->acceptInvitation->handle($request->user(), $invitation);

        return redirect()->route('workspaces.index');
    }

    /**
     * @throws Exception
     */
    public function reject(Request $request, Invitation $invitation): RedirectResponse
    {
        abort_unless($request->user()->id === $invitation->user_id, 404);

        $invitation->delete();

        return redirect()->route('workspaces.index');
    }
}
