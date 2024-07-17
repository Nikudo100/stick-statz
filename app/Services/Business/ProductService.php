<?php

namespace App\Services\Business;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function updateOrCreateProducts(array $products)
    {
        foreach ($products as $productData) {
            try {
                Product::updateOrCreate(
                    ['nmID' => $productData['nmID']],
                    [
                        'title' => $productData['title'],
                        'vendorCode' => $productData['vendorCode'],
                        'price' => $productData['price'],
                        // другие поля
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to create or update product", [
                    'data' => $productData,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
