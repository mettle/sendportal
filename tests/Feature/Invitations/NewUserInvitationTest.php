<?php

declare(strict_types=1);

namespace Tests\Feature\Invitations;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NewUserInvitationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        putenv('SENDPORTAL_REGISTER=true');

        parent::setUp();
    }

    /** @test */
    public function a_new_user_can_register_with_an_invitation_to_an_existing_workspace()
    {
        Event::fake();

        // given
        $workspace = Workspace::factory()->create();
        $invitation = Invitation::factory()->create(
            [
                'workspace_id' => $workspace->id,
            ]
        );

        $postData = [
            'name' => $this->faker->name(),
            'email' => $invitation->email,
            'password' => $this->faker->password(8),
            'invitation' => $invitation->token,
        ];

        // when
        $response = $this->post(route('register'), $postData);

        // then
        $response->assertSessionHasNoErrors();

        /** @var User $user */
        $user = User::where('email', $postData['email'])->first();

        self::assertNotNull($user);

        self::assertEquals($postData['name'], $user->name);

        self::assertTrue($user->onWorkspace($workspace));

        $this->assertDatabaseMissing(
            'invitations',
            [
                'token' => $invitation->token,
            ]
        );
    }

    /** @test */
    public function a_user_cannot_see_the_register_form_with_an_invalid_invitation()
    {
        // when
        $response = $this->get(route('register').'?invitation=invalid_invitation');

        // then
        $response->assertRedirect(route('register'));
        $response->assertSessionHas('error', 'The invitation is no longer valid.');
    }

    /** @test */
    public function registrations_fail_validation_when_invitation_is_invalid()
    {
        // given
        $postData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(),
            'invitation' => 'invalid_invitation',
        ];

        // when
        $response = $this->post(route('register'), $postData);

        // then
        $response->assertRedirect();
        $response->assertSessionHasErrors('invitation', 'The invitation is no longer valid.');

        $user = User::where('email', $postData['email'])->first();

        self::assertNull($user);
    }
}
