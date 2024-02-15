<?php

namespace Easy2Dev\MultiTenantWizard\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CreateDatabaseCommand extends Command
{
    protected $signature = 'tenant:database:create {name : The name of the database to create}';

    protected $description = 'Create a PostgreSQL database';

    public function handle()
    {
        $userDatabase = $this->argument('name');

        try {
            DB::connection('pgsql')->statement("CREATE DATABASE $userDatabase");
            $this->info("Database $userDatabase created successfully!");
        } catch (QueryException $e) {
            $this->error("Failed to create database $userDatabase: " . $e->getMessage());
            $this->rollback($userDatabase);
        }
    }

    private function rollback($databaseName)
    {
        try {
            DB::connection('pgsql')->statement("DROP DATABASE IF EXISTS $databaseName");
            $this->info("Rollback: Database $databaseName dropped successfully!");
        } catch (QueryException $e) {
            $this->error("Rollback failed: " . $e->getMessage());
        }
    }
}