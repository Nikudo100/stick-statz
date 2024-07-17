<?php

namespace App\Services\Management;

use App\Services\Fetch\Wb\GetCarts;
use Illuminate\Support\Facades\Log;
use App\Services\Business\ProductService;

class ProductManager
{
    protected $fetchService;
    protected $productService;

    public function __construct(GetCarts $fetchService, ProductService $productService)
    {
        $this->fetchService = $fetchService;
        $this->productService = $productService;
    }

    public function syncProducts()
    {
        $products = $this->fetchService->getAllCards();
        $this->productService->updateOrCreateProducts($products);
    }
}
