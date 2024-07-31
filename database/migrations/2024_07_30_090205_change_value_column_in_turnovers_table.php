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
        Schema::table('turnovers', function (Blueprint $table) {
            $table->decimal('value', 8, 2)->change();
            $table->string('status')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnovers', function (Blueprint $table) {
            $table->integer('value')->change();
            $table->string('status')->change()->nullable(false);
        });
    }
};
