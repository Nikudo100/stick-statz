<?php

namespace App\Services\Management;

use App\Services\Business\ClusterService;

class ClusterManager
{
    protected $clusterService;

    public function __construct(ClusterService $clusterService)
    {
        $this->clusterService = $clusterService;
    }

    public function createClusters(array $clustersData)
    {
        $this->clusterService->updateOrCreateClusters($clustersData);
    }

    public function getAllClusters()
    {
        dd($this->clusterService->getAllClusters());
        return $this->clusterService->getAllClusters();
    }
}
