<?php

declare(strict_types=1);

namespace Tests\Feature\Setup;

use App\Livewire\Setup;
use App\Setup\Admin;
use App\Setup\Env;
use App\Setup\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SetupTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function the_setup_component_should_render_the_correct_view()
    {
        Livewire::test(Setup::class)
            ->assertViewIs('livewire.setup');
    }

    #[Test]
    public function the_setup_component_should_have_6_steps()
    {
        $setup = Livewire::test(Setup::class);

        self::assertCount(6, $setup->get('steps'));
    }

    #[Test]
    public function the_setup_component_should_check_the_first_step_when_mounted_and_stop_if_its_false()
    {
        $this->mock(
            Env::class,
            function ($mock) {
                $mock->shouldReceive('check')->once()->andReturn(false);
            }
        );

        $setup = Livewire::test(Setup::class);

        $setup->assertSet('active', 0);
        $setup->assertSet('progress', 0);
    }

    #[Test]
    public function the_setup_command_should_stop_on_the_admin_step_if_there_are_not_users()
    {
        // These steps depend on the local .env file and APP_URL rather than the code under test.
        $this->mock(Env::class, function ($mock) {
            $mock->shouldReceive('check')->andReturn(true);
        });
        $this->mock(Url::class, function ($mock) {
            $mock->shouldReceive('check')->andReturn(true);
        });

        $setup = Livewire::test(Setup::class);

        $setup->assertSet('active', 5);
        $setup->assertSet('progress', 83);

        $step = $setup->get('steps')[$setup->get('active')];

        self::assertEquals(Admin::class, $step['handler']);
        self::assertFalse($step['completed']);
    }
}
