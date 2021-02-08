<?php

declare(strict_types=1);

namespace App\Setup;

use Illuminate\Support\Facades\Artisan;

class Migrations implements StepInterface
{
    public const VIEW = 'setup.steps.migrations';

    /**
     * {@inheritDoc}
     */
    public function check(): bool
    {
        $migrator = app('migrator');

        $files = $migrator->getMigrationFiles($migrator->paths());

        return count(array_diff(array_keys($files), $this->getPastMigrations($migrator))) === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function run(?array $input): bool
    {
        return (bool) ! Artisan::call('migrate');
    }

    protected function getPastMigrations($migrator): array
    {
        if (!$migrator->repositoryExists()) {
            return [];
        }

        return $migrator->getRepository()->getRan();
    }
}
