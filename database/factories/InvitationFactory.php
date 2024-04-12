<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class InvitationFactory extends Factory
{
    /** @var string */
    protected $model = Invitation::class;

    public function definition(): array
    {
        return [
            'id' => Uuid::uuid4(),
            'user_id' => null,
            'workspace_id' => Workspace::factory(),
            'role' => Workspace::ROLE_MEMBER,
            'email' => $this->faker->safeEmail(),
            'token' => Str::random(40)
        ];
    }
}