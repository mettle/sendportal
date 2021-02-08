<?php

declare(strict_types=1);

namespace App\Http\Requests\Workspaces;

use App\Models\Workspace;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class WorkspaceInvitationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->ownsCurrentWorkspace();
    }

    public function validator(): ValidatorContract
    {
        $validator = Validator::make($this->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        return $validator->after(function ($validator) {
            return $this->verifyEmailNotAlreadyOnWorkspace($validator, $this->user()->currentWorkspace)
                ->verifyEmailNotAlreadyInvited($validator, $this->user()->currentWorkspace);
        });
    }

    protected function verifyEmailNotAlreadyOnWorkspace(ValidatorContract $validator, Workspace $workspace): self
    {
        if ($workspace->users()->where('email', $this->email)->exists()) {
            $validator->errors()->add('email', __('That user is already on the workspace.'));
        }

        return $this;
    }

    protected function verifyEmailNotAlreadyInvited(ValidatorContract $validator, Workspace $workspace): self
    {
        if ($workspace->invitations()->where('email', $this->email)->exists()) {
            $validator->errors()->add('email', __('That user is already invited to the workspace.'));
        }

        return $this;
    }
}
