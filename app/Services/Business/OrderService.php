<?php

namespace App\Services\Business;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function updateOrCreateOrders(array $orders)
    {
        foreach ($orders as $orderData) {
            try {
                Order::updateOrCreate(
                    ['srid' => $orderData['srid']],
                    [
                        //@TODO нужно учесть date(создание) и lastChangeDate (изменение)
                        'warehouseName' => $orderData['warehouseName'] ?? null,
                        'countryName' => $orderData['countryName'] ?? null,
                        'oblastOkrugName' => $orderData['oblastOkrugName'] ?? null,
                        'regionName' => $orderData['regionName'] ?? null,
                        // находить продукт по nmId 
                        'nmId' => $orderData['nmId'] ?? null,
                        'product_id' => $orderData['product_id'] ?? null,

                        'barcode' => $orderData['barcode'] ?? null,
                        'category' => $orderData['category'] ?? null,
                        'subject' => $orderData['subject'] ?? null,
                        'brand' => $orderData['brand'] ?? null,
                        'techSize' => $orderData['techSize'] ?? null,
                        'incomeID' => $orderData['incomeID'] ?? null,
                        'isSupply' => $orderData['isSupply'] ?? null,
                        'isRealization' => $orderData['isRealization'] ?? null,
                        'totalPrice' => $orderData['totalPrice'] ?? 0,
                        'discountPercent' => $orderData['discountPercent'] ?? 0,
                        'spp' => $orderData['spp'] ?? 0,
                        'forPay' => $orderData['forPay'] ?? 0,
                        'finishedPrice' => $orderData['finishedPrice'] ?? 0,
                        'priceWithDisc' => $orderData['priceWithDisc'] ?? 0,
                        'saleID' => $orderData['saleID'] ?? null,
                        'isCancel' => $orderData['isCancel'] ?? false,
                        'cancelDate' => $orderData['cancelDate'] ?? null,
                        'orderType' => $orderData['orderType'] ?? null,
                        'sticker' => $orderData['sticker'] ?? null,
                        'gNumber' => $orderData['gNumber'] ?? null,
                        
                        'warehouse_id' => $orderData['warehouse_id'] ?? null,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update order", [
                    'data' => $orderData,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
