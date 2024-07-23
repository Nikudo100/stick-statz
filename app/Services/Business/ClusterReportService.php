<?php

namespace App\Services\Business;

use App\Models\Cluster;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClusterReportService
{
    public function generateReport($slug)
    {
        $products = Product::all(['id', 'vendorCode']);
        $productIds = $products->pluck('id');

        // Get all necessary data in bulk
        $totalData = $this->getTotalData($productIds);
        $clusterData = ($slug === 'all') ? $this->getClusterData($productIds) : [];

        $report = [];

        foreach ($products as $product) {
            $mainData = $totalData->where('product_id', $product->id)->first();

            $productData = [
                'vendorCode' => $product->vendorCode,
                'main' => $this->formatData($mainData),
            ];

            if ($slug === 'all') {
                $clusters = Cluster::all();
                foreach ($clusters as $cluster) {
                    $clusterInfo = $clusterData->where('product_id', $product->id)
                        ->where('cluster_slug', $cluster->slug)
                        ->first();
                    $productData[$cluster->slug] = $this->formatData($clusterInfo);
                }
            } elseif ($slug !== 'main') {
                $clusterInfo = $clusterData->where('product_id', $product->id)
                    ->where('cluster_slug', $slug)
                    ->first();
                $productData[$slug] = $this->formatData($clusterInfo);
            }

            $report[] = $productData;
        }

        return $report;
    }

    protected function getTotalData($productIds)
    {
        return DB::table('products')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('orders', function ($join) {
                $join->on('products.id', '=', 'orders.product_id')
                     ->where('orders.created_at', '>=', Carbon::now()->subDays(30)->startOfDay());
            })
            ->select(
                'products.id as product_id',
                DB::raw('SUM(stocks.amount) as totalStock'),
                DB::raw('COUNT(orders.id) as orders30Days'),
                DB::raw('SUM(orders."finishedPrice") as totalFinishedPrice')
            )
            ->whereIn('products.id', $productIds)
            ->groupBy('products.id')
            ->get();
    }

    protected function getClusterData($productIds)
    {
        return DB::table('products')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('orders', function ($join) {
                $join->on('products.id', '=', 'orders.product_id')
                     ->where('orders.created_at', '>=', Carbon::now()->subDays(30)->startOfDay());
            })
            ->leftJoin('warehouses', 'stocks.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('clusters', function ($join) {
                $join->on('warehouses.name', '=', DB::raw('ANY(clusters.warehouse_ids::text[])'))
                     ->orOn('orders.regionName', '=', DB::raw('ANY(clusters.order_region_names::text[])'));
            })
            ->select(
                'products.id as product_id',
                'clusters.slug as cluster_slug',
                DB::raw('SUM(stocks.amount) as clusterStock'),
                DB::raw('COUNT(orders.id) as clusterOrders30Days')
            )
            ->whereIn('products.id', $productIds)
            ->groupBy('products.id', 'clusters.slug')
            ->get();
    }

    protected function formatData($data)
    {
        if (!$data) {
            return [
                'totalStock' => 0,
                'orders30Days' => 0,
                'avgPerDay' => 0,
                'daysToFinish' => 0,
                'reorder' => 0,
                'clusterStock' => 0,
                'clusterOrders30Days' => 0,
                'clusterAvgPerDay' => 0,
                'clusterDaysToFinish' => 0,
                'clusterReorder' => 0,
            ];
        }

        $avgPerDay = $data->orders30Days / 30;
        $daysToFinish = $avgPerDay > 0 ? $data->totalStock / $avgPerDay : 0;
        $reorder = ($avgPerDay * 45) - $data->totalStock;

        return [
            'totalStock' => $data->totalStock,
            'orders30Days' => $data->orders30Days,
            'avgPerDay' => $avgPerDay,
            'daysToFinish' => $daysToFinish,
            'reorder' => $reorder,
            'clusterStock' => $data->clusterStock ?? 0,
            'clusterOrders30Days' => $data->clusterOrders30Days ?? 0,
            'clusterAvgPerDay' => isset($data->clusterOrders30Days) ? $data->clusterOrders30Days / 30 : 0,
            'clusterDaysToFinish' => isset($data->clusterAvgPerDay) && $data->clusterAvgPerDay > 0 ? ($data->clusterStock ?? 0) / $data->clusterAvgPerDay : 0,
            'clusterReorder' => isset($data->clusterAvgPerDay) ? ($data->clusterAvgPerDay * 45) - ($data->clusterStock ?? 0) : 0,
        ];
    }
}
