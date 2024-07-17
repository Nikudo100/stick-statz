<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('warehouseName')->nullable();
            $table->string('countryName')->nullable();
            $table->string('oblastOkrugName')->nullable();
            $table->string('regionName')->nullable();
            $table->integer('nmId')->nullable();
            $table->string('barcode')->nullable();
            $table->string('category')->nullable();
            $table->string('subject')->nullable();
            $table->string('brand')->nullable();
            $table->string('techSize')->nullable();
            $table->integer('incomeID')->nullable();
            $table->boolean('isSupply')->nullable();
            $table->boolean('isRealization')->nullable();
            $table->decimal('totalPrice', 8, 2)->nullable();
            $table->integer('discountPercent')->nullable();
            $table->decimal('spp', 8, 2)->nullable();
            $table->decimal('forPay', 8, 2)->nullable();
            $table->decimal('finishedPrice', 8, 2)->nullable();
            $table->decimal('priceWithDisc', 8, 2)->nullable();
            $table->integer('saleID')->nullable();
            $table->boolean('isCancel')->nullable();
            $table->dateTime('cancelDate')->nullable();
            $table->string('orderType')->nullable();
            $table->string('sticker')->nullable();
            $table->string('gNumber')->nullable();
            $table->string('srid')->unique();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
