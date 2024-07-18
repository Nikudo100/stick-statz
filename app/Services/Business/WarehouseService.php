<?php
    
namespace App\Services\Business;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Log;

class WarehouseService
{
    public function updateOrCreateWarehouses(array $warehouses)
    {
        foreach ($warehouses as $warehouseName) {
            try {
                Warehouse::updateOrCreate(
                    ['name' => $warehouseName],
                    ['name' => $warehouseName]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update warehouse", [
                    'name' => $warehouseName,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
