<?php

declare(strict_types=1);

namespace Tests\Feature\Setup;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetupControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_the_setup_view_if_sendportal_is_not_installed()
    {
        $this
            ->get(route('setup'))
            ->assertOk()
            ->assertSeeLivewire('setup');
    }

    /** @test */
    public function it_should_redirect_the_user_to_the_login_page_if_sendportal_is_already_installed()
    {
        User::factory()->create();

        $this
            ->get(route('setup'))
            ->assertRedirect(route('login'));
    }
}
