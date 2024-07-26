<?php

namespace App\Services\Business;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Cluster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    public function updateOrCreateStocks(array $stocks)
    {
        echo 'Кл-во остатков получено с ВБ: ' . count($stocks) . PHP_EOL;

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

    public function get()
    {
        $query = "
            WITH product_data AS (
                SELECT
                    p.id,
                    p.\"vendorCode\",
                    p.title,
                    p.nmID,
                    pc.name AS category,
                    (SELECT pp.url FROM product_photos pp WHERE pp.product_id = p.id LIMIT 1) AS image
                FROM products p
                LEFT JOIN product_categories pc ON p.subjectID = pc.external_cat_id
            ),
            total_stocks AS (
                SELECT product_id, SUM(amount) AS totalStock
                FROM stocks
                GROUP BY product_id
            ),
            in_way_to_client AS (
                SELECT product_id, SUM(in_way_to_client) AS totalInWayToClient
                FROM stocks
                GROUP BY product_id
            ),
            in_way_from_client AS (
                SELECT product_id, SUM(in_way_from_client) AS totalInWayFromClient
                FROM stocks
                GROUP BY product_id
            ),
            warehouse_totals AS (
                SELECT product_id, warehouse_id, SUM(amount) AS warehouseStock
                FROM stocks
                GROUP BY product_id, warehouse_id
            )
            SELECT
                pd.id,
                pd.\"vendorCode\",
                pd.title,
                pd.nmID,
                pd.category,
                pd.image,
                COALESCE(ts.totalStock, 0) AS totalStock,
                COALESCE(iw.totalInWayToClient, 0) AS totalInWayToClient,
                COALESCE(iwf.totalInWayFromClient, 0) AS totalInWayFromClient,
                wt.warehouse_id,
                COALESCE(wt.warehouseStock, 0) AS warehouseStock
            FROM product_data pd
            LEFT JOIN total_stocks ts ON pd.id = ts.product_id
            LEFT JOIN in_way_to_client iw ON pd.id = iw.product_id
            LEFT JOIN in_way_from_client iwf ON pd.id = iwf.product_id
            LEFT JOIN warehouse_totals wt ON pd.id = wt.product_id
            WHERE wt.warehouse_id IN (SELECT warehouse_id FROM stocks WHERE amount > 0 GROUP BY warehouse_id)
            ORDER BY pd.id, wt.warehouse_id
        ";

        $stocks = DB::select($query);

        // Обработка результатов для нужного формата на фронте
        $result = [];
        foreach ($stocks as $stock) {
            $product_id = $stock->id;

            if (!isset($result[$product_id])) {
                $result[$product_id] = [
                    'id' => $stock->id,
                    'vendorCode' => $stock->vendorCode,
                    'title' => $stock->title,
                    'nmId' => $stock->nmID,
                    'category' => $stock->category,
                    'image' => $stock->image,
                    'totalStock' => $stock->totalStock,
                    'totalInWayToClient' => $stock->totalInWayToClient,
                    'totalInWayFromClient' => $stock->totalInWayFromClient,
                    'warehouses' => []
                ];
            }

            $result[$product_id]['warehouses'][$stock->warehouse_id] = $stock->warehouseStock;
        }

        return array_values($result);
    }
}
