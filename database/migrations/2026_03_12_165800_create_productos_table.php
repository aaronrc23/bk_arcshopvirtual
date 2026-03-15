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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();

            $table->decimal('valor_unitario', 10, 3);
            $table->decimal('precio_venta', 10, 3);
            $table->decimal('precio_compra', 10, 3)->default(0);
            $table->decimal('valor_mayoreo', 10, 3);
            $table->decimal('precio_mayoreo', 10, 3);
            $table->integer('cantidad_mayoreo')->nullable();

            $table->string('codigo_barras')->unique()->nullable();
            $table->string('codigo_interno')->unique()->nullable();

            $table->smallInteger('afecto_icbper')->default(0)->nullable();
            $table->decimal('factor_icbper', 15, 2)->default(0)->nullable();

            $table->char('tipo_afectacion_id', 2)
                ->nullable()
                ->charset('utf8mb4')
                ->collation('utf8mb4_general_ci');

            $table->foreign('tipo_afectacion_id')
                ->references('id')
                ->on('tipo_afectacion')
                ->noActionOnDelete();
            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->cascadeOnDelete();

            $table->char('unidad_id', 3);

            $table->foreign('unidad_id')
                ->references('id')
                ->on('unidades')
                ->cascadeOnDelete();

            $table->boolean('activo')->default(true);
            $table->boolean('destacado')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
