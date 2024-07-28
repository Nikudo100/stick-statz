<?php

namespace App\Services\Business;

use App\Models\AbcAnalysis;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbcReportService
{
    public function generateReport()
    {
        // $query = "
        //     WITH products AS (
        //         SELECT id, \"vendorCode\" FROM products
        //     ),
        //     orders_30_days AS (
        //         SELECT product_id, COUNT(*) AS orders30Days
        //         FROM orders
        //         WHERE product_id IN (SELECT id FROM products)
        //         AND \"date\" >= NOW() - INTERVAL '30 days'
        //         GROUP BY product_id
        //     )
        //     SELECT
        //         p.id,
        //         p.\"vendorCode\",
        //         COALESCE(o.orders30Days, 0) AS orders30Days,
        //     FROM products p
        //     LEFT JOIN orders_30_days o ON p.id = o.product_id
        // ";

        // $products = DB::select($query);
        // dd($products);

        $abcData = Order::select('product_id', DB::raw('SUM("priceWithDisc") as total_sales'))
            ->where('date', '>=', Carbon::now()->subDays(30)->startOfDay())
            ->groupBy('product_id')
            // ->orderByDesc('total_sales')
            ->get();

        dd($abcData);

        $productCount = $abcData->count();
        $highValue = (int) round($productCount * 0.2, 0);
        $mediumValue = (int) round($productCount * 0.75, 0);

        $abcData = $this->assignAbcStatus($abcData, $highValue, $mediumValue, $productCount);
        dd($abcData);
        foreach ($abcData as $data) {
            AbcAnalysis::create([
                'product_id' => $data['product_id'],
                'value' => $data['total_sales'],
                'status' => $data['status'],
                'created_at' => Carbon::now()
            ]);
        }
    }

    private function assignAbcStatus($products, $highValue, $mediumValue, $productCount)
    {
        $result = [];
        $highValueItems = $this->getAbcValue($products, $highValue, 'A');
        $mediumValueItems = $this->getAbcValue($products, $mediumValue, 'B', $highValueItems->pluck('product_id')->all());
        $lowValueItems = $this->getAbcValue($products, $productCount, 'C', [...$mediumValueItems->pluck('product_id')->all(), ...$highValueItems->pluck('product_id')->all()]);

        return array_merge($highValueItems->toArray(), $mediumValueItems->toArray(), $lowValueItems->toArray());
    }

    private function getAbcValue($items, int $take, string $label, array $except = [])
    {
        if ($label !== 'A') {
            $items = $items->filter(function ($i) use ($except) {
                return !in_array($i->product_id, $except);
            });
        }

        return $items->take($take)->map(function ($i) use ($label) {
            if ($label == 'A') {
                $label = $this->getAbcStatus($i->total_sales);
            }

            return [
                'product_id' => $i->product_id,
                'total_sales' => $i->total_sales,
                'status' => $label
            ];
        });
    }

    private function getAbcStatus(float $sum)
    {
        if ($sum >= 200000) return 'A+++';
        if ($sum >= 100000) return 'A++';
        if ($sum >= 50000) return 'A+';
        return 'A';
    }
}
