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
        Schema::create('moneda', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->string('moneda', 50)->nullable();
            $table->string('abreviatura', 10)->nullable();
            $table->boolean('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moneda');
    }
};
