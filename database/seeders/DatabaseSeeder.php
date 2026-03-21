<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmpresaSeeder::class,
            AuthSeeder::class,
            UnidadesSeeder::class,
            TipoComprobanteSeeder::class,
            ParametroSeeder::class,
            TipoDocumentoSeeder::class,
        ]);
    }
}
