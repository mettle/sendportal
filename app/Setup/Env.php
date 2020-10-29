<?php

namespace App\Setup;

class Env implements StepInterface
{
    const VIEW = 'setup.steps.env';

    /**
     * {@inheritDoc}
     */
    public function check(): bool
    {
        return file_exists(base_path('.env'));
    }

    /**
     * {@inheritDoc}
     */
    public function run(?array $input): bool
    {
        return copy(base_path('.env.example'), base_path('.env'));
    }
}
