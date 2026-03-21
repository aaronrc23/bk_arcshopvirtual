<?php

use App\Enums\TipoEntrada;
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
        Schema::create('mov_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')
                ->constrained('inventario')
                ->onDelete('cascade');

            $table->enum('tipo', array_column(TipoEntrada::cases(), 'value'))
                ->default(TipoEntrada::ENTRADA->value);

            // Cantidad (puede ser decimal si vendes por peso/litros, si no, use integer)
            $table->integer('cantidad');

            // Para saber el stock resultante después del movimiento (útil para auditoría rápida)
            $table->integer('stock_anterior');

            // Descripción o referencia (ej: "Venta #105", "Ajuste por rotura")
            $table->string('descripcion')->nullable();

            // Usuario que realizó el movimiento (opcional)
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mov_inventario');
    }
};
