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
                        'supplier_article' => $stockData['supplierArticle'] ?? null,
                        'warehouse_name' => $stockData['warehouseName'] ?? null,
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
        $productTotalsQuery = Product::join('stocks', 'products.id', '=', 'stocks.product_id')
        ->join('product_categories', 'products.subjectID', '=', 'product_categories.external_cat_id')
        ->leftJoin(DB::raw('(SELECT DISTINCT ON (product_id) product_id, big FROM product_photos ORDER BY product_id, created_at DESC) as product_photos'), 'products.id', '=', 'product_photos.product_id')
        ->select(
            'products.id',
            'products.vendorCode',
            'products.title',
            'products.nmID',
            'product_categories.name as category',
            'product_photos.big as img',
            DB::raw('SUM(stocks.amount) as total_amount'),
            DB::raw('SUM(stocks.in_way_to_client) as in_way_to_client'),
            DB::raw('SUM(stocks.in_way_from_client) as in_way_from_client')
        )
        ->groupBy(
            'products.id',
            'products.vendorCode',
            'products.title',
            'products.nmID',
            'product_categories.name',
            'product_photos.big'
        )
        ->orderBy('total_amount', 'DESC')
        ->get();


        $stockDataQuery = Stock::join('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
            ->select(
                'stocks.product_id',
                'warehouses.name as warehouse_name',
                DB::raw('SUM(stocks.amount) as total_amount')
            )
            ->groupBy('stocks.product_id', 'warehouses.name')
            ->havingRaw('SUM(stocks.amount) > 0')
            ->orderBy('total_amount', 'DESC')
            ->get();

        $result = [];
        $sum = [
            'total_amount' => 0,
            'in_way_to_client' => 0,
            'in_way_from_client' => 0
        ];
        foreach ($productTotalsQuery as $product) {
            $result[$product->id] = [
                'id' => $product->id,
                'vendorCode' => $product->vendorCode,
                'title' => $product->title,
                'img' => $product->img,
                'nmID' => $product->nmID,
                'category' => $product->category,
                'total_amount' => $product->total_amount,
                'in_way_to_client' => $product->in_way_to_client,
                'in_way_from_client' => $product->in_way_from_client,
                'warehouses' => []
            ];
            $sum['total_amount'] += $product->total_amount;
            $sum['in_way_to_client'] += $product->in_way_to_client;
            $sum['in_way_from_client'] += $product->in_way_from_client;
        }

        foreach ($stockDataQuery as $stock) {
            if (isset($result[$stock->product_id])) {
                $result[$stock->product_id]['warehouses'][] = [
                    'warehouse_name' => $stock->warehouse_name,
                    'total_amount' => $stock->total_amount
                ];
            }
        }


        $result['data'] = array_values($result);
        $result['wArr'] = array_unique($stockDataQuery->pluck('warehouse_name')->toArray());
        $result['sum'] = $sum;

        return $result;
    }
}
