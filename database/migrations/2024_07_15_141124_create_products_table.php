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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('nmId');
            $table->integer('imtID');
            $table->integer('subjectID');
            $table->string('vendorCode');
            $table->string('title');
            $table->text('description');
            $table->json('dimensions');
            $table->decimal('price', 8, 2);
            $table->string('article');
            $table->json('images');
            $table->timestamps();
            $table->decimal('price_base', 8, 2);
            $table->integer('discount_base');
            $table->string('barcode');
            $table->string('size');
            $table->json('package_size');
            $table->date('end_sale');
            // $table->foreignId('account_id')->constrained();
            // $table->foreignId('brand_id')->constrained();
            // $table->foreignId('status_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
