<?php

declare(strict_types=1);

namespace Sendportal\Base\Http\Controllers\Workspaces;

use Illuminate\Http\Request;
use Sendportal\Base\Models\Workspace;
use Illuminate\Http\RedirectResponse;

class SwitchWorkspaceController
{
    public function switch(Request $request, Workspace $workspace): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->onWorkspace($workspace), 404);

        $user->switchToWorkspace($workspace);

        return redirect()->route('sendportal.dashboard');
    }
}
