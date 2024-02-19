<?php

namespace Easy2Dev\MultiTenantWizard\Commands;

use Easy2Dev\MultiTenantWizard\Helpers\DBArtisanHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantDBSeedCommand extends Command
{
    protected $signature = 'tenant:db:seed {prefix} {--path=}';
    protected $description = 'Seed data into databases with a specific prefix';

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
                if ($this->migrationTableExists($database)) {
                    $this->seedData($database, $migrationPath);

                } else {
                    $this->info("No migrations found in database '$database' to rollback.");
                }
            } catch (\Throwable $exception) {
                $this->error("An error occurred while seeding data for database '$database': " . $exception->getMessage());
            }
        }
    }

    protected function getDatabasesWithPrefix(string $prefix): array
    {
        $query = "SELECT datname FROM pg_database WHERE datistemplate = false AND datname LIKE '{$prefix}%'";
        $databaseNames = DB::connection('pgsql')->select($query);

        return array_column($databaseNames, 'datname');
    }

    protected function migrationTableExists(string $database): bool
    {
        $tableName = config('database.migrations');
        $query = "SELECT EXISTS (
            SELECT 1
            FROM information_schema.tables
            WHERE table_schema = 'public'
            AND table_name = '$tableName'
        )";
        $result = DB::connection($database)->select($query);
        return (bool) $result[0]->exists;
    }

    protected function seedData(string $userDatabase, string $migrationPath): void
    {
        $this->call('db:seed', [
            '--database' => $userDatabase,
            '--force' => true,
            '--path' => $migrationPath,
        ]);

        $this->info("Seeding data for database '$userDatabase'.");
        $this->line(Artisan::output());
    }
}
