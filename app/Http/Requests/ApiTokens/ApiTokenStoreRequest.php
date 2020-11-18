<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiTokens;

use Illuminate\Foundation\Http\FormRequest;

class ApiTokenStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string']
        ];
    }
}