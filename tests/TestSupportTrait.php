<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Testing\TestResponse;
use App\Models\User;
use App\Models\Workspace;

trait TestSupportTrait
{
    public function assertLoginRedirect(TestResponse $response): void
    {
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function createWorkspaceUser(Workspace $workspace, array $overrides = []): User
    {
        $user = User::factory()->create($overrides);

        $workspace->users()->attach($user, ['role' => Workspace::ROLE_MEMBER]);

        return $user;
    }

    /**
     * Create a user with attached workspace.
     */
    protected function createUserWithWorkspace(): User
    {
        return Workspace::factory()->create()->owner;
    }

    /**
     * Create a user with attached workspace, returning both workspace and user.
     */
    protected function createUserAndWorkspace(): array
    {
        /** @var Workspace $workspace */
        $workspace = Workspace::factory()->create();

        return [$workspace, $workspace->owner];
    }

    /**
     * Log in the given user.
     */
    protected function loginUser(User $user): void
    {
        auth()->login($user);
    }
}
