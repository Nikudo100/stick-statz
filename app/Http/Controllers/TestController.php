<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\TurnoverReportService;

class TestController extends Controller
{
    public function index(TurnoverReportService $testS)
    {
        // dd($testS->generateReport()[0]);
        $testS->generateReport();
        return 1;
    }
}
