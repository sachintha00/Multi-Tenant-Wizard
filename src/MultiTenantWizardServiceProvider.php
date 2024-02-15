<?php

namespace Easy2Dev\MultiTenantWizard;

use Easy2Dev\MultiTenantWizard\Commands\CreateDatabaseCommand;
use Easy2Dev\MultiTenantWizard\Commands\MigrateAllCommand;
use Easy2Dev\MultiTenantWizard\Commands\MigrateCommand;
use Easy2Dev\MultiTenantWizard\Commands\MigrateRefreshCommand;
use Easy2Dev\MultiTenantWizard\Commands\MigrateResetCommand;
use Easy2Dev\MultiTenantWizard\Commands\MigrateRollbackAllCommand;
use Illuminate\Support\ServiceProvider;

class MultiTenantWizardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            CreateDatabaseCommand::class,
            MigrateCommand::class,
            MigrateAllCommand::class,
            MigrateRollbackAllCommand::class,
            MigrateRefreshCommand::class,
            MigrateResetCommand::class,
        ]);
    }
}