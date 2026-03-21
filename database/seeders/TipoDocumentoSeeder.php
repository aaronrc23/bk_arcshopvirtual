<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_documento')->insert([
            [
                'id' => '0',
                'descripcion' => 'SIN DOCUMENTO',
            ],
            [
                'id' => '1',
                'descripcion' => 'DNI',
            ],
            [
                'id' => '4',
                'descripcion' => 'CARNET DE EXTRANJERIA',
            ],
            [
                'id' => '6',
                'descripcion' => 'RUC',
            ],
            [
                'id' => '7',
                'descripcion' => 'PASAPORTE',
            ],
            [
                'id' => 'A',
                'descripcion' => 'CED. DIPLOMATICA DE IDENTIDAD',
            ],
            [
                'id' => 'B',
                'descripcion' => 'DOC. IDENTIDAD PAIS RESIDENCIA',
            ],
        ]);
    }
}
