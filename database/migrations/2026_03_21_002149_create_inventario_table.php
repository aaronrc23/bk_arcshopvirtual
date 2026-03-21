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
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('productos')
                ->cascadeOnDelete();

            $table->foreignId('almacen_id')
                ->constrained('almacenes')
                ->cascadeOnDelete();

            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->nullable()->default(0);

            $table->boolean('estado')->default(true)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // equivalente a @Unique(['product', 'almacen'])
            $table->unique(['product_id', 'almacen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};
