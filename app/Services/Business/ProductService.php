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
    protected $categoryService;

    public function __construct(ProductCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function updateOrCreateProducts(array $products)
    {
        echo 'Кл-во продуктов получено с ВБ: ' . count($products) . PHP_EOL;

        // $cats = [ array_unique(array_column($products, 'subjectID')) => array_unique(array_column($products, 'subjectName')) ];

        // $this->categoryService->updateOrCreateCategories($cats);

        $uniqueSubjectIDs = array_unique(array_column($products, 'subjectID'));
        $categories = [];
        foreach ($uniqueSubjectIDs as $key => $subjectID) {
            $categories[$subjectID] = $products[$key]['subjectName'];
        }
        $this->categoryService->updateOrCreateCategories($categories);

        foreach ($products as $productData) {
            try {
                $product = Product::updateOrCreate(
                    ['nmID' => $productData['nmID']],
                    [
                        'imtID' => $productData['imtID'] ?? 0,
                        'nmUUID' => $productData['nmUUID'] ?? null,
                        'subjectID' => $productData['subjectID'] ?? null,
                        'vendorCode' => $productData['vendorCode'] ?? null,
                        'brand' => $productData['brand'] ?? null,
                        'title' => $productData['title'] ?? null,
                        'description' => $productData['description'] ?? null,
                        'dimensions' => $productData['dimensions'] ?? [],
                        'video' => $productData['video'] ?? null,
                    ]
                );

                $this->syncPhotos($product, $productData['photos'] ?? []);
                $this->syncCharacteristics($product, $productData['characteristics'] ?? []);
                $this->syncSizes($product, $productData['sizes'] ?? []);
                $this->syncTags($product, $productData['tags'] ?? []);

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
                ['product_id' => $product->id, 'big' => $photoData['big'] ?? null],
                [
                    'c246x328' => $photoData['c246x328'] ?? null,
                    'c516x688' => $photoData['c516x688'] ?? null,
                    'square' => $photoData['square'] ?? null,
                    'tm' => $photoData['tm'] ?? null,
                ]
            );
        }
    }

    private function syncCharacteristics(Product $product, array $characteristics)
    {
        foreach ($characteristics as $charData) {
            ProductCharacteristic::updateOrCreate(
                ['product_id' => $product->id, 'external_char_id' => $charData['id'] ?? 0],
                [
                    'name' => $charData['name'] ?? null,
                    'value' => $charData['value'] ?? [],
                ]
            );
        }
    }

    private function syncSizes(Product $product, array $sizes)
    {
        foreach ($sizes as $sizeData) {
            ProductSize::updateOrCreate(
                ['product_id' => $product->id, 'external_chrtID' => $sizeData['chrtID'] ?? 0],
                [
                    'techSize' => $sizeData['techSize'] ?? null,
                    'wbSize' => $sizeData['wbSize'] ?? null,
                    'skus' => $sizeData['skus'] ?? [],
                ]
            );
        }
    }

    private function syncTags(Product $product, array $tags)
    {
        foreach ($tags as $tagData) {
            ProductTag::updateOrCreate(
                ['product_id' => $product->id, 'external_tag_id' => $tagData['id'] ?? 0],
                [
                    'name' => $tagData['name'] ?? null,
                    'color' => $tagData['color'] ?? null,
                ]
            );
        }
    }
}
