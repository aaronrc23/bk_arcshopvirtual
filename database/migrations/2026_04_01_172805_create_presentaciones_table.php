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
        Schema::create('presentaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained("productos")->cascadeOnDelete();

            $table->string('medida')->nullable();
            $table->decimal('unidades_por_caja', 10, 3);

            $table->decimal('largo', 10, 3)->nullable();
            $table->decimal('ancho', 10, 3)->nullable();
            $table->decimal('alto', 10, 3)->nullable();
            $table->decimal('peso', 10, 3)->nullable();

            $table->char('unidad_id', 3)->nullable();

            $table->boolean('es_principal')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentaciones');
    }
};
