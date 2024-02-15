<?php

namespace Easy2Dev\MultiTenantWizard\Commands;

use Easy2Dev\MultiTenantWizard\Helpers\DBArtisanHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateRefreshCommand extends Command
{
    protected $signature = 'tenant:migrate:refresh {prefix} {--path=}';
    protected $description = 'Refresh migrations for all databases with a specific prefix';

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
                $this->call('migrate:refresh', [
                    '--database' => $database,
                    '--path' => $migrationPath,
                ]);
            } catch (\Throwable $exception) {
                $this->error("An error occurred while refreshing migrations for database '$database': " . $exception->getMessage());
            }
        }
    }

    protected function getDatabasesWithPrefix(string $prefix): array
    {
        $query = "SELECT datname FROM pg_database WHERE datistemplate = false AND datname LIKE '{$prefix}%'";
        $databaseNames = DB::connection('pgsql')->select($query);

        return array_column($databaseNames, 'datname');
    }
}