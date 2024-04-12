<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkspaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workspace::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'owner_id' => User::factory(),
        ];
    }

    public function configure(): WorkspaceFactory
    {
        return $this->afterCreating(static function (Workspace $workspace) {
            $workspace->users()->attach($workspace->owner_id, ['role' => Workspace::ROLE_OWNER]);
        });
    }
}
