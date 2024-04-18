<?php

declare(strict_types=1);

namespace Tests\Feature\Workspaces;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @group workspace_user_test
     */
    public function an_unauthenticated_user_cannot_view_the_workspace_user_index()
    {
        $response = $this->get(route('users.index'));

        $this->assertLoginRedirect($response);
    }

    /**
     * @test
     *
     * @group workspace_user_test
     */
    public function users_cannot_view_workspace_users_for_a_workspace_they_do_not_own()
    {
        $user = User::factory()
            ->hasAttached(
                Workspace::factory()->count(1),
                ['role' => Workspace::ROLE_MEMBER]
            )
            ->create();

        $this->actingAs($user);

        $response = $this->get(route('users.index'));

        $response->assertStatus(404);
    }

    /**
     * @test
     *
     * @group workspace_user_test
     */
    public function users_can_view_workspace_users_for_a_workspace_they_do_own()
    {
        $user = $this->createUserWithWorkspace();

        $this->actingAs($user);
        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertSee($user->name);
    }

    /**
     * @test
     *
     * @group workspace_user_test
     */
    public function workspace_owners_can_remove_users_from_their_workspace()
    {
        $user = $this->createUserWithWorkspace();
        $workspace = $user->currentWorkspace();

        $otherUser = $this->createWorkspaceUser($workspace);

        self::assertTrue($otherUser->onWorkspace($workspace));

        $this->actingAs($user);
        $this->delete(route('users.destroy', $otherUser->id));

        self::assertFalse($otherUser->fresh()->onWorkspace($workspace));
    }

    /**
     * @test
     *
     * @group workspace_user_test
     */
    public function workspace_owners_cannot_remove_themselves_from_their_workspace()
    {
        [$workspace, $user] = $this->createUserAndWorkspace();

        $this->actingAs($user);
        $response = $this->delete(route('users.destroy', $user->id));

        $response->assertRedirect();

        self::assertTrue($user->onWorkspace($workspace));
    }

    /**
     * @test
     *
     * @group workspace_user_test
     */
    public function only_workspace_owners_can_remove_users_from_a_workspace()
    {
        $user = User::factory()
            ->hasAttached(
                Workspace::factory()->count(1),
                ['role' => Workspace::ROLE_MEMBER]
            )
            ->create();

        $this->actingAs($user);

        $workspace = $user->currentWorkspace();

        $otherUser = $this->createWorkspaceUser($workspace);

        $response = $this->delete(route('users.destroy', $otherUser->id));

        $response->assertStatus(404);
    }
}
