<?php

declare(strict_types=1);

namespace Tests\Feature\Workspaces;

use App\Models\Workspace;
use App\Models\User;
use App\Services\Workspaces\AddWorkspaceMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkspacesControllerTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /** @test */
    function a_user_can_see_an_index_of_their_workspaces()
    {
        // given
        $user = User::factory()->create();

        $workspaces = Workspace::factory()->count(2)->create(['owner_id' => $user->id]);

        foreach ($workspaces as $workspace) {
            (new AddWorkspaceMember())->handle($workspace, $user, Workspace::ROLE_OWNER);
        }

        // when
        $this->loginUser($user);
        $response = $this->get(route('workspaces.index'));

        // then
        $response->assertOk();
        $response->assertSee($workspaces[0]->name);
        $response->assertSee($workspaces[1]->name);
    }

    /** @test */
    function a_user_can_create_a_new_workspace()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $newWorkspaceName = $this->faker->company;

        // when
        $this->loginUser($user);
        $response = $this->post(
            route('workspaces.store'),
            [
                'name' => $newWorkspaceName
            ]
        );

        // then
        $response->assertRedirect(route('workspaces.index'));

        $this->assertDatabaseHas(
            'workspaces',
            [
                'name' => $newWorkspaceName,
                'owner_id' => $user->id
            ]
        );

        $newWorkspace = Workspace::where('name', $newWorkspaceName)->first();

        $this->assertDatabaseHas(
            'workspace_users',
            [
                'workspace_id' => $newWorkspace->id,
                'user_id' => $user->id,
                'role' => Workspace::ROLE_OWNER
            ]
        );
    }
}
