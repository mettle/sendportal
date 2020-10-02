<?php

declare(strict_types=1);

namespace Tests\Feature\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Sendportal\Base\Models\Campaign;
use Sendportal\Base\Models\Provider;
use Sendportal\Base\Models\ProviderType;
use Tests\TestCase;

class AuthConfigDisabledTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    public function setUp(): void
    {
        putenv("SENDPORTAL_REGISTER=false");
        putenv("SENDPORTAL_PASSWORD_RESET=false");

        parent::setUp();
    }

    /** @test */
    function the_registration_routes_result_in_404()
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register')->assertNotFound();

        $this->get('email/verify')->assertNotFound();
        $this->get('email/verify/{id}/{hash}')->assertNotFound();
        $this->post('email/resend')->assertNotFound();
    }

    /** @test */
    function the_password_reset_routes_result_in_404()
    {
        $this->get('password/reset')->assertNotFound();
        $this->post('password/email')->assertNotFound();
        $this->get('password/reset/123')->assertNotFound();
        $this->post('password/reset')->assertNotFound();
    }
}
