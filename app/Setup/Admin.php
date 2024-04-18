<?php

declare(strict_types=1);

namespace App\Setup;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Admin implements StepInterface
{
    public const VIEW = 'setup.steps.admin';

    /**
     * {@inheritDoc}
     */
    public function check(): bool
    {
        return User::count() > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function run(?array $input): bool
    {
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => now(),
            'password' => Hash::make($input['password']),
        ]);

        $workspace = Workspace::create([
            'name' => $input['company'],
            'owner_id' => $user->id,
        ]);

        $user->workspaces()->attach($workspace->id, [
            'role' => Workspace::ROLE_OWNER,
        ]);

        return true;
    }

    public function validate(array $input): array
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'company' => ['required', 'string'],
        ];

        $validator = Validator::make($input, $validationRules);

        return $validator->validate();
    }
}
