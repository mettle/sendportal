<?php

declare(strict_types=1);

namespace App\Setup;

use Illuminate\Support\Facades\Artisan;

class Key implements StepInterface
{
    public const VIEW = 'setup.steps.key';

    /**
     * {@inheritDoc}
     */
    public function check(): bool
    {
        if (config('app.key')) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function run(?array $input): bool
    {
        return (bool) Artisan::call('key:generate');
    }
}
