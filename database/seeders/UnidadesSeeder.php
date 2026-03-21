<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            [
                "id" => "NIU",
                "descripcion" => "Unidad"
            ],
            [
                "id" => "KG",
                "descripcion" => "Kilogramo"
            ],
            [
                "id" => "LT",
                "descripcion" => "Litro"
            ],
            [
                "id" => "ML",
                "descripcion" => "Mililitro"
            ],
            [
                "id" => "MT",
                "descripcion" => "Metro"
            ],
            [
                "id" => "YD",
                "descripcion" => "Yarda"
            ],
            [
                "id" => "PA",
                "descripcion" => "Paquete"
            ],
            [
                "id" => "PR",
                "descripcion" => "Par"
            ],
            [
                "id" => "TN",
                "descripcion" => "Tonelada"
            ],
            [
                "id" => "VR",
                "descripcion" => "Rollo"
            ],
            [
                "id" => "ZZ",
                "descripcion" => "Unidad de Medida No Definida"
            ]
        ];
        DB::table('unidades')->insert($unidades);
    }
}
