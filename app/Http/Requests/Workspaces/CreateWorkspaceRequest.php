<?php

declare(strict_types=1);

namespace App\Http\Requests\Workspaces;

use Illuminate\Foundation\Http\FormRequest;

class CreateWorkspaceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255']
        ];
    }
}
