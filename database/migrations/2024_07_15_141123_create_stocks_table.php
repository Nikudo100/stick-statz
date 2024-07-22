<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->nullable();
            $table->string('sku_external_id')->nullable();
            $table->string('supplier_article')->nullable(); // добавлено для supplierArticle
            $table->string('warehouse_name')->nullable(); // добавлено для warehouseName
            $table->integer('quantityFull')->nullable();
            $table->integer('in_way_to_client')->nullable();
            $table->integer('in_way_from_client')->nullable();
            $table->string('techSize')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('discount')->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade'); // добавлено для warehouse_id
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade'); // добавлено для product_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
