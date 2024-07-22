<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Business\ClusterReportService;

class ClusterController extends Controller
{
    protected $clusterReportService;

    public function __construct(ClusterReportService $clusterReportService)
    {
        $this->clusterReportService = $clusterReportService;
    }

    public function show($slug)
    {
        $data = $this->clusterReportService->generateReport($slug);
        // dd($data);
        return view('clusters.show', compact('data', 'slug'));
    }
}

