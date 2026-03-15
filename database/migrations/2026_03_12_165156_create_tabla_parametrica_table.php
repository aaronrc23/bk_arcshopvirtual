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
        Schema::create('tabla_parametrica', function (Blueprint $table) {
            $table->id();
            $table->char('tipo', 1);
            $table->string('codigo', 5)->nullable();
            $table->string('descripcion', 200)->nullable();
            $table->boolean('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabla_parametrica');
    }
};
