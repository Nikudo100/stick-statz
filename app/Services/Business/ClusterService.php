<?php

namespace App\Services\Business;

use App\Models\Cluster;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClusterService
{
    public function updateOrCreateClusters(array $data)
    {
        foreach ($data as $name => $info) {
            try {
                Cluster::updateOrCreate(
                    ['name' => $name],
                    [
                        'slug' => Str::slug($name),
                        'warehouse_ids' => $info['warehouses'],
                        'order_region_names' => $info['regions'],
                        'sort' => Cluster::max('sort') + 1, // Присваиваем значение сортировки
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update cluster", [
                    'name' => $name,
                    'data' => $info,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
