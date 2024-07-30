<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\AbcReportService;

class AbcReportController extends Controller
{
    public function index(AbcReportService $AbcR){
        $data = $AbcR->get();
        // dd($data);
        // dd(reset($data));
        return view('abc.show', ['abc' => $data]);
    }
}
