<?php

namespace Easy2Dev\MultiTenantWizard\Commands;

use Easy2Dev\MultiTenantWizard\Helpers\DBArtisanHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateResetCommand extends Command
{
    protected $signature = 'tenant:migrate:reset {prefix}';
    protected $description = 'Reset migrations for all databases with a specific prefix';

    public function handle()
    {
        $prefix = $this->argument('prefix');

        $databases = $this->getDatabasesWithPrefix($prefix);

        if (empty($databases)) {
            $this->info('No databases found with the specified prefix.');
            return;
        }

        foreach ($databases as $database) {
            try {
                DBArtisanHelper::configure($database);
                $this->resetMigrations($database);
            } catch (\Throwable $exception) {
                $this->error("An error occurred while resetting migrations for database '$database': " . $exception->getMessage());
            }
        }
    }

    protected function getDatabasesWithPrefix(string $prefix): array
    {
        $query = "SELECT datname FROM pg_database WHERE datistemplate = false AND datname LIKE '{$prefix}%'";
        $databaseNames = DB::connection('pgsql')->select($query);

        return array_column($databaseNames, 'datname');
    }

    protected function resetMigrations(string $userDatabase): void
    {
        $this->call('migrate:reset', [
            '--database' => $userDatabase,
            '--force' => true,
        ]);

        $this->info("Resetting migrations for database '$userDatabase'.");
        $this->line(Artisan::output());
    }
}