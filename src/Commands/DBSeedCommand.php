<?php

namespace Easy2Dev\MultiTenantWizard\Commands;

use Easy2Dev\MultiTenantWizard\Helpers\DBArtisanHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DBSeedCommand extends Command
{
    protected $signature = 'tenant:db:seed {prefix}';
    protected $description = 'Seed data into databases with a specific prefix';

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
                $this->seedData($database);
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

    protected function seedData(string $userDatabase): void
    {
        $this->call('db:seed', [
            '--database' => $userDatabase,
            '--force' => true,
        ]);

        $this->info("Seeding data for database '$userDatabase'.");
        $this->line(Artisan::output());
    }
}