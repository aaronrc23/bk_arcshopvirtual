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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id();
            $table->char('tipodoc', 1);
            $table->char('ruc', 11);
            $table->string('razon_social', 100);
            $table->string('nombre_comercial', 100)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('departamento', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('distrito', 100)->nullable();
            $table->string('urbanizacion', 100)->nullable();
            $table->string('telefono', 6)->nullable();
            $table->char('ubigeo', 6)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('usuario_sol', 20)->nullable();
            $table->string('clave_sol')->nullable();
            $table->string('cert_path')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('api_id')->nullable();
            $table->string('api_clave')->nullable();
            $table->string('username_api')->nullable();
            $table->string('password_api')->nullable();
            $table->boolean('estado_api')->default(false);
            $table->text('token_api')->nullable();
            $table->text('refresh_token_api')->nullable();
            $table->boolean('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
