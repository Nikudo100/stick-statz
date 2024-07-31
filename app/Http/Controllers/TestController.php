<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\TurnoverReportService;
use App\Services\Management\StockManager;

class TestController extends Controller
{
    public function index(StockManager $testS)
    {
        // dd($testS->generateReport()[0]);
        // $testS->generateReport();
        $testS->syncStocks();
        return 1;
    }
}
