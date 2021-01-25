<?php

declare(strict_types=1);

namespace App\Setup;

interface StepInterface
{
    /**
     * Run the action.
     *
     * @param array|null $input
     * @return bool
     */
    public function run(?array $input): bool;

    /**
     * Check the step.
     *
     * @return bool
     */
    public function check(): bool;
}
