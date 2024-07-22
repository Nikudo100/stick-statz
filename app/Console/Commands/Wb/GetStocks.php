<?php

namespace App\Console\Commands\Wb;

use Illuminate\Console\Command;
use App\Services\Management\StockManager;

class GetStocks extends Command
{
    protected $signature = 'wb:get-stocks';
    protected $description = 'Get stocks from Wildberries and save to database';
    protected $stockManager;

    public function __construct(StockManager $stockManager)
    {
        parent::__construct();
        $this->stockManager = $stockManager;
    }

    public function handle()
    {
        $this->info('Syncing stocks from Wildberries...');
        $this->stockManager->syncStocks();
        $this->info('Stocks synced successfully!');
    }
}
