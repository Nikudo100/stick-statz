<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Management\ProductManager;
class TestController extends Controller
{
    public function index(ProductManager $productManager)
    {
        return $productManager->syncProducts();
    }
}
