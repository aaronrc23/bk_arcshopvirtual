<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa = [
            [
                'id' => 1,
                'tipodoc' => '6',
                'ruc' => '',
                'razon_social' => '',
                'estado' => true,
                'estado_api' => false,
            ]
        ];



        // Empresa::insert($empresa);
        DB::table('empresa')->insert($empresa);
    }
}
