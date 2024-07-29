<?php

namespace App\Services\Management;

use Carbon\Carbon;
use App\Services\Fetch\Wb\GetOrders;
use App\Services\Business\OrderService;
use App\Models\Order;
use App\Jobs\SyncOrdersJob;

class   OrderManager
{
    protected $getOrders;
    protected $orderService;

    public function __construct(GetOrders $getOrders, OrderService $orderService)
    {
        $this->getOrders = $getOrders;
        $this->orderService = $orderService;
    }

    public function syncOrders($dateFrom = null, $flag = 0)
    {
        // Обдумать получение данных заказов
        if (blank($dateFrom)) {
            $dateFrom = Carbon::now()->subDays(33)->startOfDay()->format('Y-m-d\TH:i');
            echo 'd: '. $dateFrom;
            // $latestOrder = Order::orderBy('date', 'desc')->first();
            // if ($latestOrder) {
            //     $dateFrom = Carbon::parse($latestOrder->date)->subDays(3)->format('Y-m-d');
            // } else {
            //     $dateFrom = '2000-01-01';
            // }
        }
        // $nowDate = Carbon::now()->startOfDay();

        echo 'Start Fetch Orders' . PHP_EOL;
        $orders = $this->getOrders->fetchOrders($dateFrom, $flag);
        echo 'End Fetch Orders' . PHP_EOL;
        if ($orders) {
            $this->orderService->updateOrCreateOrders($orders);
        }
    }

    public function getOrdersLastMonth()
    {
        $currentDate = Carbon::now()->startOfDay();
        $dates = [];
        //Можно было юзать 30 дней, но взял с запасом
        for ($i = 0; $i < 33; $i++) {
            $dates[] = $currentDate->copy()->subDays($i)->format('Y-m-d H:i:s');
        }
        dump($dates);
        foreach ($dates as $index => $date) {
            $delay = now()->addMinutes($index)->addSeconds($index * 10);
            echo 'delay: ' . $delay . PHP_EOL;
            SyncOrdersJob::dispatch($date)->delay($delay);
        }
    }
}
