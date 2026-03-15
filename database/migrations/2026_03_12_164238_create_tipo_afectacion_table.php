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
        Schema::create('tipo_afectacion', function (Blueprint $table) {
            $table->char('id', 2)->primary()
                ->charset('utf8mb4')
                ->collation('utf8mb4_general_ci');

            $table->string('descripcion', 50)
                ->nullable()
                ->charset('utf8mb4')
                ->collation('utf8mb4_general_ci');

            $table->char('letra', 1)
                ->nullable()
                ->charset('utf8mb4')
                ->collation('utf8mb4_general_ci');

            $table->char('codigo', 4)
                ->nullable()
                ->charset('utf8mb4')
                ->collation('utf8mb4_general_ci');

            $table->char('nombre', 3)
                ->nullable()
                ->charset('utf8mb4')
                ->collation('utf8mb4_general_ci');

            $table->char('tipo', 3)
                ->nullable()
                ->charset('utf8mb4')
                ->collation('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_afectacion');
    }
};
