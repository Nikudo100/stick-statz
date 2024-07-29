<?php

namespace App\Services\Business;

use App\Models\AbcAnalysis;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbcReportService
{
    // public function get()
    // {
    //     // $subQuery = AbcAnalysis::select('product_id', DB::raw('MAX(created_at) as latest_analysis'))
    //     //     ->groupBy('product_id');

    //     $abc = Product::select(
    //         'products.id',
    //         'products.vendorCode',
    //         'products.title',
    //         'products.nmID',
    //         'product_categories.name as category',
    //         'product_photos.big as img',
    //         'abc_analyses.value',
    //         'abc_analyses.status',
    //         'abc_analyses.created_at'
    //     )
    //         ->leftJoin('product_categories', 'products.subjectID', '=', 'product_categories.external_cat_id')
    //         ->leftJoin('product_photos', function ($join) {
    //             $join->on('products.id', '=', 'product_photos.product_id')
    //                 ->whereRaw('product_photos.id = (SELECT MIN(id) FROM product_photos WHERE product_photos.product_id = products.id)');
    //         })
    //         ->join('abc_analyses', 'products.id', '=', 'abc_analyses.product_id')
    //         ->orderBy('abc_analyses.value', 'DESC')
    //         ->get();

    //     return $abc;
    // }
    public function get()
    {
        $products = Product::select(
            'products.id',
            'products.vendorCode',
            'products.title',
            'products.nmID',
            'product_categories.name as category',
            'product_photos.big as img'
        )
            ->leftJoin('product_categories', 'products.subjectID', '=', 'product_categories.external_cat_id')
            ->leftJoin('product_photos', function ($join) {
                $join->on('products.id', '=', 'product_photos.product_id')
                    ->whereRaw('product_photos.id = (SELECT MIN(id) FROM product_photos WHERE product_photos.product_id = products.id)');
            })
            ->join('abc_analyses', 'products.id', '=', 'abc_analyses.product_id')
            // ->orderBy('abc_analyses.created_at', 'DESC')
            ->orderBy('abc_analyses.value', 'DESC')
            ->get();

        $abcData = AbcAnalysis::select('product_id', 'value', 'status', 'created_at')
            ->orderBy('created_at', 'DESC')
            ->orderBy('value', 'DESC')
            ->get();

        $result = [];
        foreach ($products as $product) {
            $productReports = $abcData->where('product_id', $product->id)->mapWithKeys(function ($report) {
                return [$report->created_at->format('Y-m-d H:i:s') => [
                    'value' => $report->value,
                    'status' => $report->status,
                ]];
            });

            $result[] = [
                'id' => $product->id,
                'vendorCode' => $product->vendorCode,
                'title' => $product->title,
                'nmID' => $product->nmID,
                'category' => $product->category,
                'img' => $product->img,
                'reports' => $productReports
            ];
        }

        return $result;
    }



    public function generateReport()
    {
        $abcData = Product::join('orders', 'products.id', '=', 'orders.product_id')
            ->select(
                'products.id as product_id',
                'products.vendorCode',
                'products.title',
                'products.nmID',
                DB::raw('SUM(orders."priceWithDisc") as total_sales')
            )
            ->where('orders.date', '>=', DB::raw('DATE_TRUNC(\'day\', NOW()) - INTERVAL \'30 days\''))
            ->groupBy(
                'products.id',
                'products.vendorCode',
                'products.title',
                'products.nmID'
            )
            ->orderBy(DB::raw('SUM(orders."priceWithDisc")'), 'DESC')
            ->get();

        $productCount = $abcData->count();
        $highValue = (int) round($productCount * 0.2, 0);
        $mediumValue = (int) round($productCount * 0.75, 0);
        $abcData = $this->assignAbcStatus($abcData, $highValue, $mediumValue, $productCount);

        foreach ($abcData as $data) {
            AbcAnalysis::create([
                'product_id' => $data['product_id'],
                'value' => round($data['total_sales']),
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
            $total_sales = $i->total_sales ?? 0; // Установите в 0, если значение null

            if ($label == 'A') {
                $label = $this->getAbcStatus($total_sales);
            }

            return [
                'product_id' => $i->product_id,
                'total_sales' => $total_sales,
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
