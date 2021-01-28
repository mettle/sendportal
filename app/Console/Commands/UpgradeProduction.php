<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Traits\HasSendportalCommandUtilities;
use App\Traits\HasSendportalMigrationHandlers;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Sendportal\Base\SendportalBaseServiceProvider;

class UpgradeProduction extends BaseCommand
{
    use HasSendportalCommandUtilities;
    use HasSendportalMigrationHandlers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upgrade the application to the latest version.';

    /** @var Migrator */
    protected $migrator;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->migrator = app('migrator');

        $this->intro();

        $this->checkMigrations();
        $this->checkVendorAssets();

        return 0;
    }

    /**
     * Publish assets.
     */
    protected function checkVendorAssets(): void
    {
        $this->callSilent(
            'vendor:publish',
            [
                '--provider' => SendportalBaseServiceProvider::class,
                '--force' => true
            ]
        );

        $this->info('✅ Published frontend assets');
    }
}
