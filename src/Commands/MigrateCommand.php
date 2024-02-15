<?php

namespace Easy2Dev\MultiTenantWizard\Commands;

use Easy2Dev\MultiTenantWizard\Helpers\DBArtisanHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class MigrateCommand extends Command
{
    protected $signature = 'tenant:migrate {database} {--path=}';
    protected $description = 'Run migrations for a specific tenant database';

    public function handle()
    {
        $database = $this->argument('database');
        $migrationPath = $this->option('path') ?? 'database/migrations/tenant';

        try {
            DBArtisanHelper::configure($database);
            $this->runMigrations($database, $migrationPath);
        } catch (Throwable $exception) {
            $this->error('An error occurred: ' . $exception->getMessage());
        }
    }

    protected function runMigrations(string $database, string $migrationPath): void
    {
        $this->call('migrate', [
            '--database' => $database,
            '--path' => $migrationPath,
            // '--force' => true,
        ]);

        $this->info('Dynamic migrations executed successfully.');
        $this->line(Artisan::output());
    }
}