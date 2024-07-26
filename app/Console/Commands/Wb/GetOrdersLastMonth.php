<?php

namespace App\Console\Commands\Wb;

use Illuminate\Console\Command;
use App\Services\Management\OrderManager;

class GetOrdersLastMonth extends Command
{
    protected $signature = 'wb:get-orders-last-month';
    protected $description = 'Get orders from the last month';

    protected $orderManager;

    public function handle(OrderManager $orderManager)
    {
        $this->info("JOBS to Syncing Orders by Month from Wildberries... Will be started...");
        $orderManager->getOrdersLastMonth();
    }
}
