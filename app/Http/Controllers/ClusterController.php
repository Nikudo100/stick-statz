<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;
use App\Services\Business\ClusterService;

class ClusterController extends Controller
{
    public function index()
    {
        $clusters = Cluster::all();
        return view('welcome', compact('clusters'));
    }

    public function show($slug, ClusterService $clusterService)
    {
        $data = $clusterService->generateReport($slug);
        // dd($data);
        return view('clusters.show', compact('data', 'slug'));
    }
}
