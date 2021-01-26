<?php

declare(strict_types=1);

namespace App\Setup;

use Illuminate\Support\Facades\Validator;

class Url implements StepInterface
{
    use WritesToEnvironment;

    public const VIEW = 'setup.steps.url';

    /**
     * {@inheritDoc}
     */
    public function check(): bool
    {
        return config('app.url') && config('app.url') !== 'http://localhost';
    }

    /**
     * {@inheritDoc}
     */
    public function run(?array $input): bool
    {
        $this->writeToEnvironmentFile('APP_URL', $input['url']);

        config()->set('app.url', $input['url']);

        return true;
    }

    public function validate(array $input = []): array
    {
        $validationRules = [
            'url' => ['required', 'url']
        ];

        $validator = Validator::make($input, $validationRules);

        return $validator->validate();
    }
}
