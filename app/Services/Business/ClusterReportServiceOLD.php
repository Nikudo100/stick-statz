<?php

namespace App\Services\Business;

use App\Models\Cluster;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClusterReportServiceOLD
{
    public function generateReport($slug)
    {
        $products = Product::all(['id', 'vendorCode']);
        $report = [];

        if ($slug === 'main' || $slug === 'all') {
            foreach ($products as $product) {
                $productData = [
                    'vendorCode' => $product->vendorCode,
                    'main' => $this->calculateProductData($product),
                ];

                if ($slug === 'all') {
                    $clusters = Cluster::all();
                    foreach ($clusters as $cluster) {
                        $productData[$cluster->slug] = $this->calculateProductData($product, $cluster);
                    }
                }

                $report[] = $productData;
            }
        } else {
            $cluster = Cluster::where('slug', $slug)->firstOrFail();
            foreach ($products as $product) {
                $report[] = [
                    'vendorCode' => $product->vendorCode,
                    $slug => $this->calculateProductData($product, $cluster),
                ];
            }
        }
        // dd($report[12]);
        return $report;
    }

    protected function calculateProductData(Product $product, Cluster $cluster = null)
    {
        $data = [
            'totalStock' => Stock::where('product_id', $product->id)->sum('amount'),
            'orders30Days' => Order::where('product_id', $product->id)
                                    ->where('date', '>=', Carbon::now()->subDays(30)->startOfDay())
                                    ->count(),
        ];

        $data['avgPerDay'] = $data['orders30Days'] / 30;
        $data['daysToFinish'] = $data['avgPerDay'] > 0 ? $data['totalStock'] / $data['avgPerDay'] : 0;
        $data['reorder'] = ($data['avgPerDay'] * 45) - $data['totalStock'];

        if ($cluster) {
            $warehouseIds = $this->getWarehouseIds($cluster->warehouse_ids);

            $data['clusterStock'] = Stock::where('product_id', $product->id)
                                         ->whereIn('warehouse_id', $warehouseIds)
                                         ->sum('amount');

            $data['clusterOrders30Days'] = Order::where('product_id', $product->id)
                                                ->whereIn('regionName', $cluster->order_region_names)
                                                ->where('date', '>=', Carbon::now()->subDays(30)->startOfDay())
                                                ->count();

            $data['clusterAvgPerDay'] = $data['clusterOrders30Days'] / 30;
            $data['clusterDaysToFinish'] = $data['clusterAvgPerDay'] > 0 ? $data['clusterStock'] / $data['clusterAvgPerDay'] : 0;
            $data['clusterReorder'] = ($data['clusterAvgPerDay'] * 45) - $data['clusterStock'];
        }

        return $data;
    }

    protected function getWarehouseIds(array $warehouseNames)
    {
        return DB::table('warehouses')->whereIn('name', $warehouseNames)->pluck('id')->toArray();
    }
}
