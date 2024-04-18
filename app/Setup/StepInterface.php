<?php

declare(strict_types=1);

namespace App\Setup;

interface StepInterface
{
    /**
     * Run the action.
     */
    public function run(?array $input): bool;

    /**
     * Check the step.
     */
    public function check(): bool;
}
