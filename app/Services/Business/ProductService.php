<?php

namespace App\Services\Business;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\ProductCharacteristic;
use App\Models\ProductSize;
use App\Models\ProductTag;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function updateOrCreateProducts(array $products)
    {

        foreach ($products as $productData) {
            try {
                // Проверка наличия subjectID
                if (isset($productData['subjectID']) && !empty($productData['subjectID'])) {
                    ProductCategory::updateOrCreate(
                        ['external_cat_id' => $productData['subjectID']],
                        ['name' => $productData['subjectName']]
                    );
                } else {
                    Log::error("Missing subjectID in product data", ['data' => $productData]);
                    continue;
                }

                $product = Product::updateOrCreate(
                    ['nmID' => $productData['nmID']],
                    [
                        'imtID' => $productData['imtID'],
                        'nmUUID' => $productData['nmUUID'],
                        'vendorCode' => $productData['vendorCode'],
                        'brand' => $productData['brand'],
                        'title' => $productData['title'],
                        'description' => $productData['description'],
                        'dimensions' => $productData['dimensions'],
                    ]
                );

                $this->syncPhotos($product, $productData['photos']);
                $this->syncCharacteristics($product, $productData['characteristics']);
                $this->syncSizes($product, $productData['sizes']);
                $this->syncTags($product, $productData['tags']);

            } catch (\Exception $e) {
                Log::error("Failed to create or update product", [
                    'data' => $productData,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function syncPhotos(Product $product, array $photos)
    {
        foreach ($photos as $photoData) {
            ProductPhoto::updateOrCreate(
                ['product_id' => $product->id, 'big' => $photoData['big']],
                [
                    'c246x328' => $photoData['c246x328'],
                    'c516x688' => $photoData['c516x688'],
                    'square' => $photoData['square'],
                    'tm' => $photoData['tm'],
                ]
            );
        }
    }

    private function syncCharacteristics(Product $product, array $characteristics)
    {
        foreach ($characteristics as $charData) {
            ProductCharacteristic::updateOrCreate(
                ['product_id' => $product->id, 'external_char_id' => $charData['id']],
                [
                    'name' => $charData['name'],
                    'value' => $charData['value'],
                ]
            );
        }
    }

    private function syncSizes(Product $product, array $sizes)
    {
        foreach ($sizes as $sizeData) {
            ProductSize::updateOrCreate(
                ['product_id' => $product->id, 'external_chrtID' => $sizeData['chrtID']],
                [
                    'techSize' => $sizeData['techSize'],
                    'wbSize' => $sizeData['wbSize'] ?? '',
                    'skus' => $sizeData['skus'],
                ]
            );
        }
    }

    private function syncTags(Product $product, array $tags)
    {
        foreach ($tags as $tagData) {
            ProductTag::updateOrCreate(
                ['product_id' => $product->id, 'external_tag_id' => $tagData['id']],
                [
                    'name' => $tagData['name'],
                    'color' => $tagData['color'],
                ]
            );
        }
    }
}
