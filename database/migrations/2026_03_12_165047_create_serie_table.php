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
        Schema::create('serie', function (Blueprint $table) {
            $table->id();
            $table->char('serie', 4);
            $table->char('correlativo');
            $table->char('codigo');
            $table->unsignedBigInteger('tipo_comprobante_id')->notnull();
            $table->foreign('tipo_comprobante_id')->references('id')->on('tipo_comprobante');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serie');
    }
};
