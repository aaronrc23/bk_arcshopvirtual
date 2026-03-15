<?php

use App\Enums\TipoAlm;
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
        Schema::create('almacenes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->enum('tipo', [TipoAlm::FISICO->value, TipoAlm::VIRTUAL->value, TipoAlm::MIXTO->value])->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('is_principal')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacenes');
    }
};
