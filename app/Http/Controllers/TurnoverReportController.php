<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\TurnoverReportService;

class TurnoverReportController extends Controller
{
    public function index(TurnoverReportService $turnoverReportService){
        $data = $turnoverReportService->get();
        // dd(reset($data));
        return view('turnover.show', ['turnover' => $data]);
    }
}
