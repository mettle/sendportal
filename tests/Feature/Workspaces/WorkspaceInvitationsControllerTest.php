<?php

declare(strict_types=1);

namespace Tests\Feature\Workspaces;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Workspace;
use App\Services\Workspaces\AddWorkspaceMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WorkspaceInvitationsControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function an_invitation_can_be_sent_to_a_new_user()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $email = $this->faker->safeEmail();

        $postData = [
            'email' => $email,
        ];

        // when
        $this->actingAs($user);
        $response = $this->post(route('users.invitations.store', $postData));

        // then
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas(
            'invitations',
            [
                'workspace_id' => $workspace->id,
                'email' => $email,
                'user_id' => null,
            ]
        );
    }

    /** @test */
    public function an_invitation_can_be_sent_to_an_existing_user()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $existingInviteUser = User::factory()->create();

        $postData = [
            'email' => $existingInviteUser->email,
        ];

        // when
        $this->actingAs($user);
        $response = $this->post(route('users.invitations.store', $postData));

        // then
        $response->assertRedirect(route('users.index'));

        // existing user's invitation will be accepted automatically, and the invitation will be deleted
        $this->assertDatabaseEmpty('invitations');
    }

    /** @test */
    public function non_owners_cannot_invite_new_members()
    {
        // given
        $user = User::factory()->create();
        $workspace = Workspace::factory()->create();

        (new AddWorkspaceMember())->handle($workspace, $user, Workspace::ROLE_MEMBER);

        $email = $this->faker->safeEmail();

        $postData = [
            'email' => $email,
        ];

        // when
        $this->actingAs($user);
        $response = $this->post(route('users.invitations.store', $postData));

        // then
        $response->assertStatus(404);

        $this->assertDatabaseMissing(
            'invitations',
            [
                'workspace_id' => $workspace->id,
                'email' => $email,
            ]
        );
    }

    /** @test */
    public function invitations_can_be_retracted()
    {
        // given
        [$workspace, $user] = $this->createUserAndWorkspace();

        $invitation = Invitation::factory()->create(
            [
                'workspace_id' => $workspace->id,
            ]
        );

        // when
        $this->actingAs($user);
        $response = $this->delete(route('users.invitations.destroy', $invitation));

        // then
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing(
            'invitations',
            [
                'id' => $invitation->id,
            ]
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }
}
