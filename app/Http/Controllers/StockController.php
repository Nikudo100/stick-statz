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

        // dd($stocks['wArr']);
        return view('stocks.show', ['data' => $stocks['data'], 'warehouses' => $stocks['wArr'],'sum' => $stocks['sum']]);
    }
}
