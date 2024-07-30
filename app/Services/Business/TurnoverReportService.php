<?php

namespace App\Services\Business;

use App\Models\Turnover;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TurnoverReportService
{
    public function get()
    {
        // Получаем все продукты
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
            ->join('turnovers', 'products.id', '=', 'turnovers.product_id')
            // ->orderBy('turnovers.created_at', 'DESC')
            ->join('abc_analyses', 'products.id', '=', 'abc_analyses.product_id')
            // ->orderBy('abc_analyses.created_at', 'DESC')
            ->orderBy('abc_analyses.value', 'DESC')
            ->get();

        $TurnoverData = Turnover::select('product_id', 'value', 'status', 'created_at')
            ->orderBy('created_at', 'DESC')
            // ->orderBy('value', 'DESC')
            ->get();

        $result = [];
        foreach ($products as $product) {
            $productReports = $TurnoverData->where('product_id', $product->id)->mapWithKeys(function ($report) {
                return [$report->created_at->format('Y-m-d H:i:s') => [
                    'value' => $report->value,
                    'status' => $report->status,
                ]];
            });

            $result[$product->id] = [
                'id' => $product->id,
                'vendorCode' => $product->vendorCode,
                'title' => $product->title,
                'nmID' => $product->nmID,
                'category' => $product->category,
                'img' => $product->img,
                'reports' => $productReports,
            ];
        }

        return $result;
    }

    public function generateReport()
    {
        $query = <<<SQL
        WITH products AS (
            SELECT id, "vendorCode" FROM products
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
            AND "date" >= DATE_TRUNC('day', NOW()) - INTERVAL '30 days'
            GROUP BY product_id
        ),
        latest_abc AS (
            SELECT
                product_id,
                status,
                ROW_NUMBER() OVER (PARTITION BY product_id ORDER BY created_at DESC) AS rn
            FROM abc_analyses
        )
        SELECT
            p.id,
            p."vendorCode",
            COALESCE(ts.totalStock, 0) AS totalStock,
            COALESCE(o.orders30Days, 0) AS orders30Days,
            COALESCE(o.orders30Days, 0) / 30.0 AS avgPerDay,
            CASE
                WHEN COALESCE(o.orders30Days, 0) > 0 THEN COALESCE(ts.totalStock, 0) / (COALESCE(o.orders30Days, 0) / 30.0)
                ELSE 0
            END AS daysToFinish,
            la.status
        FROM products p
        LEFT JOIN total_stocks ts ON p.id = ts.product_id
        LEFT JOIN orders_30_days o ON p.id = o.product_id
        LEFT JOIN latest_abc la ON p.id = la.product_id AND la.rn = 1
        ORDER BY orders30Days DESC;
    SQL;

        $products = DB::select($query);
        // dd($products);
        // Turnover::truncate();

        foreach ($products as $data) {
            Turnover::create([
                'product_id' => $data->id,
                'value' => round($data->daystofinish, 2),
                'status' => $data->status ?? null,
                'created_at' => Carbon::now()
            ]);
        }
    }
}
