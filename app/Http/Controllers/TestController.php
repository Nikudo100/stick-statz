<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\AbcReportService;

class TestController extends Controller
{
    public function index(AbcReportService $testS)
    {
        // dd($testS->generateReport()[0]);
        $testS->generateReport();
        return 1;
    }
}
