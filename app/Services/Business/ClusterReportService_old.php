<?php

namespace App\Services\Business;

use App\Models\Cluster;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ClusterReportService
{
    public function generateReport($slug)
    {
        $products = Product::with(['stocks', 'orders'])->get();
        $report = [];

        if ($slug === 'main') {
            foreach ($products as $product) {
                $report[] = $this->calculateMainProductData($product);
            }
        } elseif ($slug === 'all') {
            $report['main'] = [];
            foreach ($products as $product) {
                $report['main'][] = $this->calculateMainProductData($product);
            }

            $clusters = Cluster::all();
            foreach ($clusters as $cluster) {
                $report[$cluster->slug] = [];
                foreach ($products as $product) {
                    $report[$cluster->slug][] = $this->calculateClusterProductData($product, $cluster);
                }
            }
        } else {
            $cluster = Cluster::where('slug', $slug)->firstOrFail();
            foreach ($products as $product) {
                $report[] = $this->calculateClusterProductData($product, $cluster);
            }
        }

        return $report;
    }

    protected function calculateMainProductData(Product $product)
    {
        return [
            'vendorCode' => $product->vendorCode,
            'totalStock' => $product->getTotalStock(),
            'orders30Days' => $product->getOrdersCountByMonth(),
            'avgPerDay' => $product->getAvgOrdersPerDay(),
            'daysToFinish' => $product->getTurnoverByOrders(),
            'reorder' => $product->getAdditionalOrder(),
        ];
    }

    protected function calculateClusterProductData(Product $product, Cluster $cluster)
    {
        $warehouseIds = $this->getWarehouseIds($cluster->warehouse_ids);
        $clusterStock = $product->getClusterStock($warehouseIds);
        $clusterOrders30Days = $product->getClusterOrdersCountByMonth($cluster->order_region_names);
        $clusterAvgPerDay = $clusterOrders30Days / 30;
        $clusterDaysToFinish = $clusterAvgPerDay > 0 ? $clusterStock / $clusterAvgPerDay : 0;
        $clusterReorder = ($clusterAvgPerDay * 45) - $clusterStock;

        return [
            'vendorCode' => $product->vendorCode,
            'totalStock' => $product->getTotalStock(),
            'orders30Days' => $product->getOrdersCountByMonth(),
            'avgPerDay' => $product->getAvgOrdersPerDay(),
            'daysToFinish' => $product->getTurnoverByOrders(),
            'reorder' => $product->getAdditionalOrder(),
            'clusterStock' => $clusterStock,
            'clusterOrders30Days' => $clusterOrders30Days,
            'clusterAvgPerDay' => $clusterAvgPerDay,
            'clusterDaysToFinish' => $clusterDaysToFinish,
            'clusterReorder' => $clusterReorder,
        ];
    }

    protected function getWarehouseIds(array $warehouseNames)
    {
        return DB::table('warehouses')->whereIn('name', $warehouseNames)->pluck('id')->toArray();
    }
}
