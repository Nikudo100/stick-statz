<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Management\OrderManager;

class SyncOrders extends Command
{
    protected $signature = 'wb:sync-orders {dateFrom}';
    protected $description = 'Synchronize orders from Wildberries API';

    protected $orderManager;

    public function handle(OrderManager $orderManager)
    {
        $dateFrom = $this->argument('dateFrom');
        $orderManager->syncOrders($dateFrom);
        $this->info('Orders synchronized successfully.');
    }
}
