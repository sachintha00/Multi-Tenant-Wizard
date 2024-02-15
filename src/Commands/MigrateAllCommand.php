<?php

namespace Easy2Dev\MultiTenantWizard\Commands;

use Easy2Dev\MultiTenantWizard\Helpers\DBArtisanHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateAllCommand extends Command
{
    protected $signature = 'tenant:migrate:all {prefix} {--path=}';
    protected $description = 'Run migrations for all databases with a specific prefix';

    public function handle()
    {
        $prefix = $this->argument('prefix');
        $migrationPath = $this->option('path') ?? 'database/migrations/tenant';

        $databases = $this->getDatabasesWithPrefix($prefix);

        if (empty($databases)) {
            $this->info('No databases found with the specified prefix.');
            return;
        }

        foreach ($databases as $database) {
            try {
                DBArtisanHelper::configure($database);
                $this->runMigrations($database, $migrationPath);
            } catch (\Throwable $exception) {
                $this->error("An error occurred while migrating database '$database': " . $exception->getMessage());
            }
        }
    }

    protected function getDatabasesWithPrefix(string $prefix): array
    {
        $query = "SELECT datname FROM pg_database WHERE datistemplate = false AND datname LIKE '{$prefix}%'";
        $databaseNames = DB::connection('pgsql')->select($query);

        return array_column($databaseNames, 'datname');
    }

    protected function runMigrations(string $userDatabase, string $migrationPath): void
    {
        $this->call('migrate', [
            '--database' => $userDatabase,
            '--path' => $migrationPath,
        ]);

        $this->info("Executing migrations for database '$userDatabase'.");
        $this->line(Artisan::output());
    }
}