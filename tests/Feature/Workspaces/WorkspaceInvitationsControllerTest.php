<?php

declare(strict_types=1);

namespace Tests\Feature\Workspaces;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Sendportal\Base\Models\Invitation;
use Sendportal\Base\Models\Workspace;
use Sendportal\Base\Models\User;
use Sendportal\Base\Services\Workspaces\AddWorkspaceMember;
use Tests\TestCase;

class WorkspaceInvitationsControllerTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /** @test */
    function an_invitation_can_be_sent_to_a_new_user()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $email = $this->faker->safeEmail;

        $postData = [
            'email' => $email
        ];

        // when
        $this;
        $response = $this->post(route('sendportal.users.invitations.store', $postData));

        // then
        $response->assertRedirect(route('sendportal.users.index'));

        $this->assertDatabaseHas('invitations', [
            'workspace_id' => Sendportal::currentWorkspaceId(),
            'email' => $email,
            'user_id' => null
        ]);
    }

    /** @test */
    function an_invitation_can_be_sent_to_an_existing_user()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $existingInviteUser = factory(User::class)->create();

        $postData = [
            'email' => $existingInviteUser->email
        ];

        // when
        $this;
        $response = $this->post(route('sendportal.users.invitations.store', $postData));

        // then
        $response->assertRedirect(route('sendportal.users.index'));

        $this->assertDatabaseHas('invitations', [
            'workspace_id' => Sendportal::currentWorkspaceId(),
            'email' => $existingInviteUser->email,
            'user_id' => $existingInviteUser->id
        ]);
    }

    /** @test */
    function non_owners_cannot_invite_new_members()
    {
        // given
        $user = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create();

        (new AddWorkspaceMember())->handle($workspace, $user, Workspace::ROLE_MEMBER);

        $email = $this->faker->safeEmail;

        $postData = [
            'email' => $email
        ];

        // when
        $this;
        $response = $this->post(route('sendportal.users.invitations.store', $postData));

        // then
        $response->assertStatus(404);

        $this->assertDatabaseMissing('invitations', [
            'workspace_id' => Sendportal::currentWorkspaceId(),
            'email' => $email
        ]);
    }

    /** @test */
    function invitations_can_be_retracted()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $invitation = factory(Invitation::class)->create([
            'workspace_id' => Sendportal::currentWorkspaceId()
        ]);

        // when
        $this;
        $response = $this->delete(route('sendportal.users.invitations.destroy', $invitation));

        // then
        $response->assertRedirect(route('sendportal.users.index'));

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }
}
