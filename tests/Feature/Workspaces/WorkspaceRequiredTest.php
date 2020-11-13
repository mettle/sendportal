<?php

declare(strict_types=1);

namespace Tests\Feature\Workspaces;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkspaceRequiredTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;


    /**
     * @test
     * @group workspace_user_test
     */
    public function user_gets_404_if_no_workspace_provided()
    {
        // a user is required to avoid 302 redirect by the auth middleware
        // and to actually reach the RequireWorkspace middleware
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get(route('sendportal.dashboard'));

        $response->assertStatus(404);
    }

    /**
     * @test
     * @group workspace_user_test
     */
    public function user_gets_403_if_no_workspace_provided_on_api_request()
    {
        $response = $this->get(route('sendportal.api.subscribers.index'));

        $response->assertStatus(403);
    }
}