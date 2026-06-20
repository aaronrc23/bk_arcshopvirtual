<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255)->nullable();
            $table->string('subtitulo', 500)->nullable();
            $table->string('url_imagen', 500);
            $table->string('enlace', 500)->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
