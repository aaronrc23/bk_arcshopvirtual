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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('apellidos')->nullable();
            $table->string('photo')->nullable();
            $table->string('direccion')->nullable();
            $table->bigInteger('dni')->nullable()->unique();
            $table->bigInteger('telefono')->nullable();
            $table->string('genero')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('referencia')->nullable();
            $table->string('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
