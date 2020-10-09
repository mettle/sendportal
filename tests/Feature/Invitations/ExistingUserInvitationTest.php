<?php

declare(strict_types=1);

namespace Tests\Feature\Invitations;

use App\Workspace;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ExistingUserInvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_their_invitations()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $newWorkspace = factory(Workspace::class)->create();

        $newWorkspace->invitations()->create([
            'id' => Uuid::uuid4(),
            'user_id' => $user->id,
            'role' => Workspace::ROLE_MEMBER,
            'email' => $user->email,
            'token' => Str::random(40),
        ]);

        // when
        $response = $this->actingAs($user)
            ->get(route('workspaces.index'));

        // then
        $response->assertSee($newWorkspace->name);
        $response->assertSee('Accept');
        $response->assertSee('Reject');
    }

    /** @test */
    public function a_user_cannot_see_another_users_invitations()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $secondUser = factory(User::class)->create();
        $newWorkspace = factory(Workspace::class)->create();

        $newWorkspace->invitations()->create([
            'id' => Uuid::uuid4(),
            'user_id' => $secondUser->id,
            'role' => Workspace::ROLE_MEMBER,
            'email' => $secondUser->email,
            'token' => Str::random(40),
        ]);

        // when
        $response = $this->actingAs($user)
            ->get(route('workspaces.index'));

        // then
        $response->assertDontSee($newWorkspace->name);
    }

    /** @test */
    public function a_user_can_accept_valid_invitations()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $newWorkspace = factory(Workspace::class)->create();

        $invitation = $newWorkspace->invitations()->create([
            'id' => Uuid::uuid4(),
            'user_id' => $user->id,
            'role' => Workspace::ROLE_MEMBER,
            'email' => $user->email,
            'token' => Str::random(40),
        ]);

        // when
        $response = $this->actingAs($user)
            ->post(route('workspaces.invitations.accept', $invitation));

        // then
        $response->assertRedirect(route('workspaces.index'));

        $this->assertTrue($user->fresh()->onWorkspace($newWorkspace));
    }

    /** @test */
    public function a_user_can_reject_invitations()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $newWorkspace = factory(Workspace::class)->create();

        $invitation = $newWorkspace->invitations()->create([
            'id' => Uuid::uuid4(),
            'user_id' => $user->id,
            'role' => Workspace::ROLE_MEMBER,
            'email' => $user->email,
            'token' => Str::random(40),
        ]);

        // when
        $response = $this->actingAs($user)
            ->post(route('workspaces.invitations.reject', $invitation));

        // then
        $response->assertRedirect(route('workspaces.index'));

        $this->assertFalse($user->fresh()->onWorkspace($newWorkspace));

        $this->assertDatabaseMissing('invitations', [
            'id' => $invitation->id
        ]);
    }

    /** @test */
    public function a_user_cannot_accept_an_expired_invitation()
    {
        // given
        $user = $this->createUserWithWorkspace();

        $newWorkspace = factory(Workspace::class)->create();

        $invitation = $newWorkspace->invitations()->create([
            'id' => Uuid::uuid4(),
            'user_id' => $user->id,
            'role' => Workspace::ROLE_MEMBER,
            'email' => $user->email,
            'token' => Str::random(40),
        ]);

        $invitation->created_at = $invitation->created_at->subWeeks(2);
        $invitation->save();

        // when
        $this->actingAs($user)
            ->post(route('workspaces.invitations.accept', $invitation));

        // then
        $this->assertFalse($user->fresh()->onWorkspace($newWorkspace));
    }
}
