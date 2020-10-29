<?php

namespace App\Setup;

use Illuminate\Support\Facades\Validator;

class Url implements StepInterface
{
    use WritesToEnvironment;

    const VIEW = 'setup.steps.url';

    /**
     * {@inheritDoc}
     */
    public function check(): bool
    {
        if (config('app.url') and config('app.url') !== 'http://localhost') {
            return true;
        }

        return false;
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
