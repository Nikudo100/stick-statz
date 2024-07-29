<?php

namespace App\Services\Business;

use App\Models\Cluster;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClusterService
{
    public function updateOrCreateClusters(array $data)
    {
        foreach ($data as $name => $info) {
            try {
                Cluster::updateOrCreate(
                    ['name' => $name],
                    [
                        'slug' => Str::slug($name),
                        'warehouse_ids' => $info['warehouses'],
                        'order_region_names' => $info['regions'],
                        'sort' => Cluster::max('sort') + 1, // Присваиваем значение сортировки
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update cluster", [
                    'name' => $name,
                    'data' => $info,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
    public function generateReport($slug)
    {
        $clusters = Cluster::all();

        $query = "
        WITH products AS (
            SELECT id, \"vendorCode\" FROM products
        ),
        total_stocks AS (
            SELECT product_id, SUM(amount) AS totalStock
            FROM stocks
            WHERE product_id IN (SELECT id FROM products)
            GROUP BY product_id
        ),
        orders_30_days AS (
            SELECT product_id, COUNT(*) AS orders30Days
            FROM orders
            WHERE product_id IN (SELECT id FROM products)
              AND \"date\" >= DATE_TRUNC('day', NOW()) - INTERVAL '30 days'
            GROUP BY product_id
        )
        SELECT
            p.id,
            p.\"vendorCode\",
            COALESCE(ts.totalStock, 0) AS totalStock,
            COALESCE(o.orders30Days, 0) AS orders30Days,
            COALESCE(o.orders30Days, 0) / 30.0 AS avgPerDay,
            CASE
                WHEN COALESCE(o.orders30Days, 0) > 0 THEN COALESCE(ts.totalStock, 0) / (COALESCE(o.orders30Days, 0) / 30.0)
                ELSE 0
            END AS daysToFinish,
            (COALESCE(o.orders30Days, 0) / 30.0 * 45) - COALESCE(ts.totalStock, 0) AS reorder
        FROM products p
        LEFT JOIN total_stocks ts ON p.id = ts.product_id
        LEFT JOIN orders_30_days o ON p.id = o.product_id
        ORDER BY orders30Days DESC
    ";

        $products = DB::select($query);

        $clusterData = [];
        if ($slug === 'all') {
            foreach ($clusters as $cluster) {
                $warehouseIds = $this->getWarehouseIds($cluster->warehouse_ids);

                $clusterQuery = "
                    WITH products AS (
                        SELECT id, \"vendorCode\" FROM products
                    ),
                    cluster_stocks AS (
                        SELECT product_id, SUM(amount) AS clusterStock
                        FROM stocks
                        WHERE product_id IN (SELECT id FROM products)
                          AND warehouse_id IN (" . implode(',', $warehouseIds) . ")
                        GROUP BY product_id
                    ),
                    cluster_orders_30_days AS (
                        SELECT product_id, COUNT(*) AS clusterOrders30Days
                        FROM orders
                        WHERE product_id IN (SELECT id FROM products)
                          AND orders.\"regionName\" IN ('" . implode("', '", $cluster->order_region_names) . "')
                          AND orders.\"date\" >= DATE_TRUNC('day', NOW()) - INTERVAL '30 days'
                        GROUP BY product_id
                    )
                    SELECT p.id, cs.clusterStock, co.clusterOrders30Days
                    FROM products p
                    LEFT JOIN cluster_stocks cs ON p.id = cs.product_id
                    LEFT JOIN cluster_orders_30_days co ON p.id = co.product_id
                ";

                $clusterData[$cluster->slug] = DB::select($clusterQuery);
            }
        }

        $report = [];
        foreach ($products as $product) {
            $productData = [
                'vendorCode' => $product->vendorCode,
                'main' => [
                    'totalStock' => $product->totalstock,
                    'orders30Days' => $product->orders30days,
                    'avgPerDay' => $product->orders30days / 30,
                    'daysToFinish' => $product->orders30days > 0 ? $product->totalstock / ($product->orders30days / 30) : 0,
                    'reorder' => ($product->orders30days / 30 * 45) - $product->totalstock,
                ],
            ];

            if ($slug === 'all') {
                foreach ($clusters as $cluster) {
                    $clusterProductData = collect($clusterData[$cluster->slug])->firstWhere('id', $product->id);

                    if ($clusterProductData) {
                        $productData[$cluster->slug] = [
                            'clusterStock' => $clusterProductData->clusterstock ?? 0,
                            'clusterOrders30Days' => $clusterProductData->clusterorders30days ?? 0,
                            'clusterAvgPerDay' => ($clusterProductData->clusterorders30days ?? 0) / 30,
                            'clusterDaysToFinish' => ($clusterProductData->clusterorders30days ?? 0) > 0 ? ($clusterProductData->clusterstock ?? 0) / (($clusterProductData->clusterorders30days ?? 0) / 30) : 0,
                            'clusterReorder' => (($clusterProductData->clusterorders30days ?? 0) / 30 * 45) - ($clusterProductData->clusterstock ?? 0),
                        ];
                    } else {
                        $productData[$cluster->slug] = [
                            'clusterStock' => 0,
                            'clusterOrders30Days' => 0,
                            'clusterAvgPerDay' => 0,
                            'clusterDaysToFinish' => 0,
                            'clusterReorder' => 0,
                        ];
                    }
                }
            }

            $report[] = $productData;
        }

        return $report;
    }

    protected function getWarehouseIds(array $warehouseNames)
    {
        return DB::table('warehouses')->whereIn('name', $warehouseNames)->pluck('id')->toArray();
    }
}
