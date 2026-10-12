<?php

declare(strict_types=1);

namespace App\Traits;

trait HasSendportalMigrationHandlers
{
    /**
     * Check if migrations need to be run.
     */
    protected function checkMigrations(): void
    {
        if (! $this->pendingMigrations()) {
            $this->info('✓ Database migrations are up to date');

            return;
        }

        if (! $this->runMigrations()) {
            $this->error('Database migrations must be run before setup can be completed.');

            exit;
        }
    }

    /**
     * Run the database migrations.
     */
    protected function runMigrations(): bool
    {
        $runMigrations = $this->confirm(
            'There are pending database migrations. Would you like to run migrations now?',
            true
        );

        if (! $runMigrations) {
            return false;
        }

        // The user has already confirmed, so skip migrate's production prompt. When sp:install creates
        // the .env file, the running application is still in production and that prompt defaults to no.
        if ($this->call('migrate', ['--force' => true]) !== self::SUCCESS) {
            return false;
        }

        $this->info('✓ Database migrations successful');

        return true;
    }

    /**
     * Checks to see if there are any pending migrations
     */
    protected function pendingMigrations(): bool
    {
        $files = $this->migrator->getMigrationFiles($this->getMigrationPaths());

        return (bool) collect(
            array_diff(
                array_keys($files),
                $this->getPastMigrations()
            )
        )->count();
    }

    /**
     * Get all migrations that have previously been run
     */
    protected function getPastMigrations(): array
    {
        if (! $this->migrator->repositoryExists()) {
            return [];
        }

        return $this->migrator->getRepository()->getRan();
    }
}
