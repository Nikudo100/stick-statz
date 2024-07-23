<?php
    
namespace App\Services\Business;

use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function updateOrCreateOrders(array $orders)
    {
        echo 'Кл-во заказов получено с ВБ: ' . count($orders) . PHP_EOL;
        $warehouseNames = array_unique(array_column($orders, 'warehouseName'));
        $this->warehouseService->updateOrCreateWarehouses($warehouseNames);

        foreach ($orders as $orderData) {
            try {
                $product = Product::where('nmID', $orderData['nmId'])->first();
                $warehouse = Warehouse::where('name', $orderData['warehouseName'])->first();
                // echo $orderData['date']. ' srid:'. $orderData['srid'] . PHP_EOL;
                Order::updateOrCreate(
                    ['srid' => $orderData['srid']],
                    [
                        'warehouseName' => $orderData['warehouseName'] ?? null,
                        'countryName' => $orderData['countryName'] ?? null,
                        'oblastOkrugName' => $orderData['oblastOkrugName'] ?? null,
                        'regionName' => $orderData['regionName'] ?? null,
                        'barcode' => $orderData['barcode'] ?? null,
                        'category' => $orderData['category'] ?? null,
                        'subject' => $orderData['subject'] ?? null,
                        'brand' => $orderData['brand'] ?? null,
                        'nmID' => $orderData['nmID'] ?? null,
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
                        'product_id' => $product ? $product->id : null,
                        'warehouse_id' => $warehouse ? $warehouse->id : null,
                        'date' => $orderData['date'] ?? null,
                        'lastChangeDate' => $orderData['lastChangeDate'] ?? null,
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
