<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\ClusterReportService;

class ClusterController extends Controller
{
    public function show($slug, ClusterReportService $clusterReportService)
    {
        $data = $clusterReportService->generateReport($slug);
        // dd($data);
        return view('clusters.show', compact('data', 'slug'));
    }
}

