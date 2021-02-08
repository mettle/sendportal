<?php

declare(strict_types=1);

namespace App\Setup;

use Illuminate\Support\Str;
use RuntimeException;

trait WritesToEnvironment
{
    protected function writeToEnvironmentFile(string $key, ?string $value): void
    {
        file_put_contents(app()->environmentFilePath(), preg_replace(
            "/^{$key}.*/m",
            "{$key}={$value}",
            file_get_contents(app()->environmentFilePath())
        ));

        if (!$this->checkEnvValuePresent($key, $value)) {
            throw new RuntimeException("Failed to persist environment variable value. {$key}={$value}");
        }
    }

    protected function checkEnvValuePresent(string $key, ?string $value): bool
    {
        $envContents = file_get_contents(app()->environmentFilePath());

        $needle = "{$key}={$value}";

        return Str::contains($envContents, $needle);
    }
}
