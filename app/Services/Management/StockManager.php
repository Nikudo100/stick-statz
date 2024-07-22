<?php

namespace App\Services\Management;

use App\Services\Fetch\Wb\GetStocks;
use App\Services\Business\StockService;

class StockManager
{
    protected $getStocks;
    protected $stockService;

    public function __construct(GetStocks $getStocks, StockService $stockService)
    {
        $this->getStocks = $getStocks;
        $this->stockService = $stockService;
    }

    public function syncStocks()
    {
        $stocks = $this->getStocks->fetchStocks();
        if ($stocks) {
            $this->stockService->updateOrCreateStocks($stocks);
        }
    }
}
