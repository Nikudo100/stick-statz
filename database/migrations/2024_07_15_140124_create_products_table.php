<?php
    
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('nmID')->unique();
            $table->integer('imtID')->nullable()->default(0);
            $table->uuid('nmUUID')->nullable();
            $table->string('vendorCode')->nullable();
            $table->string('brand')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->json('dimensions')->nullable();
            $table->text('video')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
