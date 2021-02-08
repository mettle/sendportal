<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\ApiToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
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

        /** @var ApiToken $apiToken */
        $apiToken = ApiToken::factory()->create(['workspace_id' => $workspace->id]);

        $response = $this->get(
            route('sendportal.api.subscribers.index'),
            [
                'Authorization' => 'Bearer ' . $apiToken->api_token
            ]
        );

        $response->assertStatus(200);
    }

    /** @test */
    public function a_401_is_thrown_when_invalid_token_provided()
    {
        [$workspace, $user] = $this->createUserAndWorkspace();

        /** @var ApiToken $apiToken */
        ApiToken::factory()->create(['workspace_id' => $workspace->id]);

        $response = $this->get(
            route('sendportal.api.subscribers.index'),
            [
                'Authorization' => 'Bearer ' . Str::random(32)
            ]
        );

        $response->assertUnauthorized();
    }

    /** @test */
    public function a_401_is_thrown_when_no_token_is_provided()
    {
        $response = $this->get(route('sendportal.api.subscribers.index'));

        $response->assertUnauthorized();
    }
}
