<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class WbMain extends Command
{
    protected $signature = 'wb:main';
    protected $description = 'Rollback all migrations, reseed the database, and run all Wildberries sync commands';

    public function handle()
    {
        $this->info('Rolling back all migrations and reseeding the database...');
        Artisan::call('migrate:fresh --seed');
        $this->info('Migrations rolled back and database reseeded.');

        $this->info('Syncing products from Wildberries...');
        Artisan::call('wb:get-products');
        $this->info('Products synchronized successfully.');

        $this->info('Syncing orders from Wildberries...');
        Artisan::call('wb:get-orders');
        $this->info('Orders synchronized successfully.');

        $this->info('Syncing stocks from Wildberries...');
        Artisan::call('wb:get-stocks');
        $this->info('Stocks synchronized successfully.');

        $this->info('Setting clusters...');
        Artisan::call('wb:set-clusters');
        $this->info('Clusters set successfully.');

        $this->info('All tasks completed successfully.');
    }
}
