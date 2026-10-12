<?php

declare(strict_types=1);

namespace Tests\Feature\Setup;

use App\Traits\HasSendportalMigrationHandlers;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MigrationHandlersTest extends TestCase
{
    #[Test]
    public function confirmed_migrations_run_without_a_second_prompt_in_production()
    {
        $this->artisan('db:wipe', ['--force' => true])->run();

        // sp:install starts before .env exists, so the application runs as production.
        $this->app['env'] = 'production';

        Artisan::registerCommand(new class () extends BaseCommand {
            use HasSendportalMigrationHandlers;

            protected $signature = 'test:check-migrations';

            protected $migrator;

            public function handle(): int
            {
                $this->migrator = app('migrator');

                $this->checkMigrations();

                return 0;
            }
        });

        $this->artisan('test:check-migrations')
            ->expectsConfirmation('There are pending database migrations. Would you like to run migrations now?', 'yes')
            ->expectsOutput('✓ Database migrations successful')
            ->assertExitCode(0);

        self::assertTrue(Schema::hasTable('users'));
    }
}
