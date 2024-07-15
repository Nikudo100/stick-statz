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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('warehouseName');
            $table->string('countryName');
            $table->string('oblastOkrugName');
            $table->string('regionName');
            $table->integer('nmId');
            $table->string('barcode');
            $table->string('category');
            $table->string('subject');
            $table->string('brand');
            $table->string('techSize');
            $table->integer('incomeID');
            $table->boolean('isSupply');
            $table->boolean('isRealization');
            $table->decimal('totalPrice', 8, 2);
            $table->integer('discountPercent');
            $table->decimal('spp', 8, 2);
            $table->decimal('forPay', 8, 2);
            $table->decimal('finishedPrice', 8, 2);
            $table->decimal('priceWithDisc', 8, 2);
            $table->integer('saleID');
            $table->boolean('isCancel');
            $table->dateTime('cancelDate')->nullable();
            $table->string('orderType');
            $table->string('sticker');
            $table->string('gNumber');
            $table->string('srid');
            // $table->foreignId('product_id')->constrained();
            // $table->foreignId('warehouse_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
