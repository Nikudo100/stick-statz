<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\TurnoverReportService;

class TurnoverReportController extends Controller
{
    public function index(TurnoverReportService $turnoverReportService){
        $data = $turnoverReportService->generateReport();
        // dd($data);
        // dd(reset($data));        
        return view('turnover.show', ['abc' => $data]);
    }
}
