<?php

namespace App\Console\Commands\Wb;

use Illuminate\Console\Command;
use App\Services\Management\OrderManager;

class GetOrders extends Command
{
    protected $signature = 'wb:get-orders {dateFrom?} {flag?}';
    protected $description = 'Synchronize orders from Wildberries API';

    protected $orderManager;

    public function handle(OrderManager $orderManager)
    {
        $this->info("Syncing orders from Wildberries...");
        $dateFrom = $this->argument('dateFrom');
        $flag = $this->argument('flag');
        $orderManager->syncOrders($dateFrom, $flag);
        $this->info('Orders synchronized successfully.');
    }
}
