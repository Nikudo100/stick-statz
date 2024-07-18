<?php

namespace App\Console\Commands\Wb;

use Illuminate\Console\Command;
use App\Services\Management\ProductManager;

class GetProducts extends Command
{
    protected $signature = 'wb:get-products';
    protected $description = 'Sync products from Wildberries';

    public function handle(ProductManager $productManager)
    {
        $this->info("Syncing products from Wildberries...");
        $productManager->syncProducts();
        $this->info("Products synced successfully.");
    }
}
