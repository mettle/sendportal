<?php

declare(strict_types=1);

namespace App\Setup;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Database implements StepInterface
{
    use WritesToEnvironment;

    public const VIEW = 'setup.steps.database';

    /**
     * {@inheritDoc}
     */
    public function check(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function run(?array $input): bool
    {
        $connection = $input['connection'];

        $fields = [
            'connection' => "database.default",
            'host' => "database.connections.{$connection}.host",
            'port' => "database.connections.{$connection}.port",
            'database' => "database.connections.{$connection}.database",
            'username' => "database.connections.{$connection}.username",
            'password' => "database.connections.{$connection}.password",
        ];

        foreach ($fields as $field => $config) {
            $this->writeToEnvironmentFile('DB_'. strtoupper($field), $input[$field]);

            config()->set($config, $input[$field]);
        }

        DB::purge(config('database.default'));

        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());

            return false;
        }
    }

    public function validate(array $input): array
    {
        $validationRules = [
            'connection' => ['required', Rule::in(array_keys(config('database.connections')))],
            'host' => ['required', 'string'],
            'port' => ['required', 'string'],
            'database' => ['required', 'string'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ];

        $validator = Validator::make($input, $validationRules);

        return $validator->validate();
    }
}
