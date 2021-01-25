<?php

declare(strict_types=1);

namespace Tests\Feature\Setup;

use App\Http\Livewire\Setup;
use App\Setup\Admin;
use App\Setup\Env;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SetupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_setup_component_should_render_the_correct_view()
    {
        Livewire::test(Setup::class)
            ->assertViewIs('livewire.setup');
    }

    /** @test */
    public function the_setup_component_should_have_6_steps()
    {
        $setup = Livewire::test(Setup::class);

        self::assertCount(6, $setup->get('steps'));
    }

    /** @test */
    public function the_setup_component_should_check_the_first_step_when_mounted_and_stop_if_its_false()
    {
        $this->mock(
            Env::class,
            function ($mock)
            {
                $mock->shouldReceive('check')->once()->andReturn(false);
            }
        );

        $setup = Livewire::test(Setup::class);

        $setup->assertSet('active', 0);
        $setup->assertSet('progress', 0);
    }

    /** @test */
    public function the_setup_command_should_stop_on_the_admin_step_if_there_are_not_users()
    {
        $setup = Livewire::test(Setup::class);

        $setup->assertSet('active', 5);
        $setup->assertSet('progress', 83);

        $step = $setup->get('steps')[$setup->get('active')];

        self::assertEquals(Admin::class, $step['handler']);
        self::assertEquals(false, $step['completed']);
    }
}
