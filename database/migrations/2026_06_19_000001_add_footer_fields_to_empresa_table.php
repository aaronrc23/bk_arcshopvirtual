<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            // Descripción para el footer
            $table->text('descripcion')->nullable()->after('nombre_comercial');

            // Redes sociales
            $table->string('facebook_url', 500)->nullable()->after('email');
            $table->string('instagram_url', 500)->nullable()->after('facebook_url');
            $table->string('twitter_url', 500)->nullable()->after('instagram_url');
            $table->string('whatsapp', 20)->nullable()->after('twitter_url');

            // Footer: Links de navegación en JSON y copyright
            $table->json('footer_links')->nullable()->after('whatsapp');
            $table->string('copyright_text', 500)->nullable()->after('footer_links');

            // Ampliar telefono de CHAR(6) a VARCHAR(20)
            $table->string('telefono', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn([
                'descripcion',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'whatsapp',
                'footer_links',
                'copyright_text',
            ]);
        });
    }
};
