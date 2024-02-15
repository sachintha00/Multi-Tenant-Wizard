<?php

namespace Easy2Dev\MultiTenantWizard\Helpers;

use Illuminate\Support\Facades\DB;

class DBArtisanHelper
{
    public static function configure(string $userDatabase): void
    {
        config([
            "database.connections.$userDatabase" => [
                'driver' => 'pgsql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '5432'),
                'database' => $userDatabase,
                'username' => env('DB_USERNAME', 'forge'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8',
                'prefix' => '',
                'schema' => 'public',
            ],
        ]);

        DB::purge($userDatabase);
        DB::reconnect($userDatabase);
    }
}