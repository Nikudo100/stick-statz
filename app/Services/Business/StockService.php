<?php

namespace App\Services\Business;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Log;

class StockService
{
    public function updateOrCreateStocks(array $stocks)
    {
        foreach ($stocks as $stockData) {
            try {
                $product = Product::where('nmID', $stockData['nmId'])->first();
                $warehouse = Warehouse::firstOrCreate(['name' => $stockData['warehouseName']]);
                
                Stock::updateOrCreate(
                    [
                        'sku_external_id' => $stockData['barcode'],
                        'warehouse_id' => $warehouse ? $warehouse->id : null,
                    ],
                    [
                        'amount' => $stockData['quantity'] ?? 0,
                        'name' => $stockData['supplierArticle'] ?? null,
                        'quantityFull' => $stockData['quantityFull'] ?? 0,
                        'in_way_to_client' => $stockData['inWayToClient'] ?? 0,
                        'in_way_from_client' => $stockData['inWayFromClient'] ?? 0,
                        'techSize' => $stockData['techSize'] ?? null,
                        'price' => $stockData['Price'] ?? 0,
                        'discount' => $stockData['Discount'] ?? 0,
                        'product_id' => $product ? $product->id : null,
                        'warehouse_id' => $warehouse ? $warehouse->id : null,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update stock", [
                    'data' => $stockData,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
