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
        dd($stocks);
        return view('stocks.show', compact('stocks'));
    }
}
