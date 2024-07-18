<?php
    
namespace App\Services\Business;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Log;

class ProductCategoryService
{
    public function updateOrCreateCategories($cats)
    {
        foreach ($cats as $external_cat_id => $name) {
            try {
                ProductCategory::updateOrCreate(
                    ['external_cat_id' => $external_cat_id],
                    ['name' => $name]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update Categories", [
                    'name' => $name,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
