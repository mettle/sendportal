<?php

declare(strict_types=1);

namespace Tests\Feature\Workspaces;

use Sendportal\Base\Models\Workspace;
use Sendportal\Base\Services\Workspaces\AddWorkspaceMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwitchWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_switch_between_workspaces()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $secondWorkspace = factory(Workspace::class)->create(['owner_id' => $user->id]);

        (new AddWorkspaceMember())->handle($secondWorkspace, $user, Workspace::ROLE_OWNER);

        // when
        $this->loginUser($user);
        $response = $this->get(route('sendportal.workspaces.switch', $secondWorkspace->id));

        // then
        $response->assertRedirect(route('sendportal.dashboard'));

        $this->assertEquals($secondWorkspace->id, $user->currentWorkspace()->id);
    }

    /** @test */
    function a_user_cannot_switch_to_a_workspace_they_do_not_belong_to()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $secondWorkspace = factory(Workspace::class)->create();

        // when
        $this->loginUser($user);
        $response = $this->get(route('sendportal.workspaces.switch', $secondWorkspace->id));

        // then
        $response->assertStatus(404);

        $this->assertEquals(Sendportal::currentWorkspaceId(), $user->currentWorkspace()->id);
    }

    /** @test */
    function a_guest_cannot_switch_workspaces()
    {
        // given
        $user = $this->createUserWithWorkspace();
        $secondWorkspace = factory(Workspace::class)->create(['owner_id' => $user->id]);

        (new AddWorkspaceMember())->handle($secondWorkspace, $user, Workspace::ROLE_OWNER);

        // when
        $response = $this->get(route('sendportal.workspaces.switch', $secondWorkspace->id));

        // then
        $response->assertRedirect(route('login'));
    }
}
