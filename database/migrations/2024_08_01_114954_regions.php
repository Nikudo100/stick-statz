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
        // Создание таблицы regions
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('cluster_id')->nullable()->constrained('clusters')->onDelete('set null');
            $table->timestamps();
        });

        // Обновление таблицы warehouses
        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreignId('cluster_id')->nullable()->constrained('clusters')->onDelete('set null');
        });

        // Переименование поля и удаление старых полей в таблице clusters
        Schema::table('clusters', function (Blueprint $table) {
            $table->renameColumn('order_region_names', 'order_region_ids');
            // $table->dropColumn('warehouse_ids');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Откат изменений в таблице clusters
        Schema::table('clusters', function (Blueprint $table) {
            $table->renameColumn('order_region_ids', 'order_region_names');
            // $table->json('warehouse_ids')->nullable();
        });

        // Удаление поля cluster_id из таблицы warehouses
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cluster_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('region_id');
        });
        // Удаление таблицы regions
        Schema::dropIfExists('regions');
    }
};
