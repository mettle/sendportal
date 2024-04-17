<?php

declare(strict_types=1);

namespace Tests\Feature\Workspaces;

use App\Models\Workspace;
use App\Services\Workspaces\AddWorkspaceMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwitchWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_switch_between_workspaces()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $secondWorkspace = Workspace::factory()->create(['owner_id' => $user->id]);

        (new AddWorkspaceMember())->handle($secondWorkspace, $user, Workspace::ROLE_OWNER);

        // when
        $this->loginUser($user);
        $response = $this->get(route('workspaces.switch', $secondWorkspace->id));

        // then
        $response->assertRedirect(route('sendportal.dashboard'));

        self::assertEquals($secondWorkspace->id, $user->currentWorkspace()->id);
    }

    /** @test */
    public function a_user_cannot_switch_to_a_workspace_they_do_not_belong_to()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $secondWorkspace = Workspace::factory()->create();

        // when
        $this->loginUser($user);
        $response = $this->get(route('workspaces.switch', $secondWorkspace->id));

        // then
        $response->assertStatus(404);

        self::assertEquals($workspace->id, $user->currentWorkspace()->id);
    }

    /** @test */
    public function a_guest_cannot_switch_workspaces()
    {
        // given
        $user = $this->createUserWithWorkspace();
        $secondWorkspace = Workspace::factory()->create(['owner_id' => $user->id]);

        (new AddWorkspaceMember())->handle($secondWorkspace, $user, Workspace::ROLE_OWNER);

        // when
        $response = $this->get(route('workspaces.switch', $secondWorkspace->id));

        // then
        $response->assertRedirect(route('login'));
    }
}
