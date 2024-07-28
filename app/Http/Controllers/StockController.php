<?php

namespace App\Http\Controllers;

use App\Services\Business\StockService;
use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function show(StockService $service)
    {
        $stocks = $service->get();
        // Создаем массив уникальных складов
        $uniqueWarehouses = collect($stocks)->flatMap(function ($product) {
            return $product['warehouses'];
        })->unique('warehouse_id')->values()->all();

        return view('stocks.show', ['data' => $stocks, 'warehouses' => $uniqueWarehouses]);
    }
}
