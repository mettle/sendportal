<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthConfigEnabledTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        putenv('SENDPORTAL_REGISTER=true');
        putenv('SENDPORTAL_PASSWORD_RESET=true');

        parent::setUp();
    }

    /** @test */
    public function the_registration_routes_result_in_200()
    {
        $this->get('/register')->assertOk();
    }

    /** @test */
    public function the_password_reset_routes_result_in_200()
    {
        $this->get('password/reset')->assertOk();
    }
}
