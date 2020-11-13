<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\ApiToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkspaceApiTokenTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;


    /**
     * @test
     * @group workspace_user_test
     */
    public function valid_api_token_grants_access()
    {
        [$workspace, $user] = $this->createUserAndWorkspace();
        $apiToken = factory(ApiToken::class)->create(['workspace_id' => $workspace->id]);

        $response = $this->get(route('sendportal.api.subscribers.index'), [
            'Authorization' => 'Bearer ' . $apiToken->api_token
        ]);

        $response->assertStatus(200);
    }
}