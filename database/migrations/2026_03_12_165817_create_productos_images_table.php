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
        Schema::create('productos_images', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->integer('orden');
            $table->boolean('is_principal')->default(false);

            // FK → producto
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_images');
    }
};
