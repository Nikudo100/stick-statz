<?php

namespace App\Services\Management;

use Carbon\Carbon;
use App\Services\Fetch\Wb\GetOrders;
use App\Services\Business\OrderService;

class OrderManager
{
    protected $getOrders;
    protected $orderService;

    public function __construct(GetOrders $getOrders, OrderService $orderService)
    {
        $this->getOrders = $getOrders;
        $this->orderService = $orderService;
    }

    public function syncOrders($dateFrom = null)
    {
        if (!$dateFrom){
            $dateFrom = date("Y-m-d");
        }
        $orders = $this->getOrders->fetchOrders($dateFrom);

        if ($orders) {
            $this->orderService->updateOrCreateOrders($orders);
        }
    }
}
