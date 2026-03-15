<?php

use App\Enums\CategoryLevel;
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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500)->unique();

            // Nivel jerárquico
            $table->enum(
                'level',
                array_column(CategoryLevel::cases(), 'value')
            )->default(CategoryLevel::CATEGORIA->value);

            // Auto-relación recursiva
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categorias')
                ->cascadeOnDelete();

            // Iconos / imágenes
            $table->string('icon', 100)->nullable();
            $table->string('imagen', 300)->nullable();

            // Orden
            $table->integer('order')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
