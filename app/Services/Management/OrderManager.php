<?php

namespace App\Services\Management;

use Carbon\Carbon;
use App\Services\Fetch\Wb\GetOrders;
use App\Services\Business\OrderService;
use App\Models\Order;

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
        if (!$dateFrom) {
            $latestOrder = Order::orderBy('created_at', 'desc')->first();
            if ($latestOrder) {
                $dateFrom = Carbon::parse($latestOrder->created_at)->subDays(2)->format('Y-m-d');
            } else {
                $dateFrom = '2000-01-01';
            }
        }
        echo 'Start Fenth Orders';
        $orders = $this->getOrders->fetchOrders($dateFrom);
        echo 'End Fenth Orders';
        if ($orders) {
            $this->orderService->updateOrCreateOrders($orders);
        }
    }
}
