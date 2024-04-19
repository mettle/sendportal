<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Workspace;
use App\Traits\HasSendportalCommandUtilities;
use App\Traits\HasSendportalMigrationHandlers;
use Exception;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RuntimeException;
use Sendportal\Base\SendportalBaseServiceProvider;

class InstallApplication extends BaseCommand
{
    use HasSendportalCommandUtilities;
    use HasSendportalMigrationHandlers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the application for a production environment.';

    /** @var Migrator */
    protected $migrator;

    public function handle(): int
    {
        $this->migrator = app('migrator');

        $this->intro();
        $this->line('');
        $this->checkEnvironment();
        $this->checkApplicationKey();
        $this->checkAppUrl();
        $this->checkDatabaseConnection();
        $this->checkMigrations();
        $this->checkAdminUserAccount();
        $this->checkVendorAssets();

        $this->info('✓ SendPortal is ready!');
        $this->line('');

        return 0;
    }

    /**
     * Check that the environment file exists. If it doesn't, then prompt the user to create it.
     */
    protected function checkEnvironment(): void
    {
        if (file_exists(base_path('.env'))) {
            $this->info('✓ .env file already exists');

            return;
        }

        $createFile = $this->confirm('The .env file does not yet exist. Would you like to create it now?', true);

        if ($createFile && copy(base_path('.env.example'), base_path('.env'))) {
            $this->info('✓ .env file has been created');
            $this->call('key:generate');

            return;
        }

        $this->error('The .env file must be created before you can continue.');

        exit;
    }

    /**
     * Check that the application key exists. If it doesn't then we'll create it automatically.
     */
    protected function checkApplicationKey(): void
    {
        if (! config('app.key')) {
            $this->call('key:generate');
        }

        $this->info('✓ Application key has been set');
    }

    /**
     * Check that the app url has been set.
     */
    protected function checkAppUrl(): void
    {
        if (config('app.url') !== 'http://localhost') {
            $this->info('✓ Application url set to '.config('app.url'));

            return;
        }

        $this->writeToEnvironmentFile('APP_URL', $this->ask('Application URL', 'https://sendportal.yourdomain.com'));
    }

    /**
     * Check to see if the app can make a database connection
     */
    protected function checkDatabaseConnection(): void
    {
        try {
            DB::connection()->getPdo();
            $this->info('✓ Database connection successful');
        } catch (Exception $e) {
            try {
                if (! $this->createDatabaseCredentials()) {
                    $this->error(
                        'A database connection could not be established. Please update your configuration and try again.'
                    );
                    $this->printDatabaseConfig();
                    exit();
                }
            } catch (RuntimeException $e) {
                $this->error('Failed to persist environment configuration.');
                exit();
            }

            $this->checkDatabaseConnection();
        }
    }

    protected function createDatabaseCredentials(): bool
    {
        $storeCredentials = $this->confirm(
            'Unable to connect to your database. Would you like to enter your credentials now?',
            true
        );

        if (! $storeCredentials) {
            return false;
        }

        $connection = $this->choice('Type', ['mysql', 'pgsql'], 0);

        $variables = [
            'DB_CONNECTION' => $connection,

            'DB_HOST' => $this->anticipate(
                'Host',
                ['127.0.0.1', 'localhost'],
                config("database.connections.{$connection}.host", '127.0.0.1')
            ),

            'DB_PORT' => $this->ask(
                'Port',
                config("database.connections.{$connection}.port", '3306')
            ),

            'DB_DATABASE' => $this->ask(
                'Database',
                config("database.connections.{$connection}.database")
            ),

            'DB_USERNAME' => $this->ask(
                'Username',
                config("database.connections.{$connection}.username")
            ),

            'DB_PASSWORD' => $this->secret(
                'Password',
                config("database.connections.{$connection}.password")
            ),
        ];

        $this->persistVariables($variables);

        return true;
    }

    /**
     * Check to see if the first admin user account has been created
     */
    protected function checkAdminUserAccount(): void
    {
        if (User::count()) {
            $this->info('✓ Admin user account exists');

            return;
        }

        $companyName = $this->getCompanyName();
        $this->createAdminUserAccount($companyName);

        $this->info('✓ Admin user account has been created');
    }

    /**
     * Prompt the user for their company/workspace name
     */
    protected function getCompanyName(): string
    {
        $this->line('');
        $this->line('Creating first admin user account and company/workspace');
        $companyName = $this->ask('Company/Workspace name');

        if (! $companyName) {
            return $this->getCompanyName();
        }

        return $companyName;
    }

    /**
     * Create the first admin user account and associate it with the company/workspace
     */
    protected function createAdminUserAccount(string $companyName): User
    {
        $this->line('');
        $this->line('Create the administrator user account');

        $name = $this->getUserParam('name');
        $email = $this->getUserParam('email');
        $password = $this->getUserParam('password');

        $user = User::create(
            [
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'api_token' => Str::random(80),
            ]
        );

        $this->storeWorkspace($user, $companyName);

        return $user;
    }

    /**
     * Validate user input
     */
    protected function getUserParam(string $param): string
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];

        if ($param === 'password') {
            $value = $this->secret(ucfirst($param));
        } else {
            $value = $this->ask(ucfirst($param));
        }

        $validator = Validator::make(
            [$param => $value],
            [
                $param => $validationRules[$param],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                $this->line((string) ($error[0]));
            }

            return $this->getUserParam($param);
        }

        return $value;
    }

    /**
     * Store the workspace
     */
    protected function storeWorkspace(User $user, string $companyName): Workspace
    {
        $workspace = Workspace::create(
            [
                'name' => $companyName,
                'owner_id' => $user->id,
            ]
        );

        $user->workspaces()->attach(
            $workspace,
            [
                'role' => Workspace::ROLE_OWNER,
            ]
        );

        return $workspace;
    }

    /**
     * Publish frontend assets
     */
    protected function checkVendorAssets(): void
    {
        $this->callSilent(
            'vendor:publish',
            [
                '--provider' => SendportalBaseServiceProvider::class,
                '--tag' => 'sendportal-assets',
                '--force' => true,
            ]
        );

        $this->info('✓ Published frontend assets');
    }

    /**
     * Print the database config to the console
     */
    protected function printDatabaseConfig(): void
    {
        $connection = config('database.default');

        $this->line('');
        $this->info('Database Configuration:');
        $this->line("- Connection: {$connection}");
        $this->line('- Host: '.config("database.connections.{$connection}.host"));
        $this->line('- Port: '.config("database.connections.{$connection}.port"));
        $this->line('- Database: '.config("database.connections.{$connection}.database"));
        $this->line('- Username: '.config("database.connections.{$connection}.username"));
        $this->line('- Password: '.config("database.connections.{$connection}.password"));
    }

    /**
     * Persist database configuration variables and purge the currently loaded connection.
     */
    protected function persistVariables(array $connectionData): void
    {
        $connection = $connectionData['DB_CONNECTION'];

        $configMap = [
            'DB_CONNECTION' => 'database.default',
            'DB_HOST' => "database.connections.{$connection}.host",
            'DB_PORT' => "database.connections.{$connection}.port",
            'DB_DATABASE' => "database.connections.{$connection}.database",
            'DB_USERNAME' => "database.connections.{$connection}.username",
            'DB_PASSWORD' => "database.connections.{$connection}.password",
        ];

        foreach ($connectionData as $envKey => $value) {
            $this->writeToEnvironmentFile($envKey, $value);
            $this->writeToConfig($configMap[$envKey], $value);
        }

        DB::purge($this->laravel['config']['database.default']);
    }

    /**
     * Write a value to a given key within the environment file.
     */
    protected function writeToEnvironmentFile(string $key, ?string $value): void
    {
        file_put_contents(
            $this->laravel->environmentFilePath(),
            preg_replace(
                $this->keyReplacementPattern($key),
                "{$key}={$value}",
                file_get_contents($this->laravel->environmentFilePath())
            )
        );

        if (! $this->checkEnvValuePresent($key, $value)) {
            throw new RuntimeException("Failed to persist environment variable value. {$key}={$value}");
        }
    }

    protected function checkEnvValuePresent(string $key, ?string $value): bool
    {
        $envContents = file_get_contents($this->laravel->environmentFilePath());

        $needle = "{$key}={$value}";

        return Str::contains($envContents, $needle);
    }

    /**
     * Get a regex pattern that will match a given environment variable by its key.
     */
    protected function keyReplacementPattern(string $key): string
    {
        return "/^{$key}.*/m";
    }

    /**
     * Write to a given key within the Laravel configuration file.
     */
    protected function writeToConfig(string $key, ?string $value): void
    {
        $this->laravel['config'][$key] = $value;
    }
}
