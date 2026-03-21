<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametroSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            ['id' => 1, 'tipo' => 'C', 'codigo' => '01', 'descripcion' => 'Anulación de la operación', 'estado' => 1],
            ['id' => 2, 'tipo' => 'C', 'codigo' => '02', 'descripcion' => 'Anulación por error en el RUC', 'estado' => 1],
            ['id' => 3, 'tipo' => 'C', 'codigo' => '03', 'descripcion' => 'Corrección por error en la descripción', 'estado' => 1],
            ['id' => 4, 'tipo' => 'C', 'codigo' => '04', 'descripcion' => 'Descuento global', 'estado' => 1],
            ['id' => 5, 'tipo' => 'C', 'codigo' => '05', 'descripcion' => 'Descuento por ítem', 'estado' => 1],
            ['id' => 6, 'tipo' => 'C', 'codigo' => '06', 'descripcion' => 'Devolución total', 'estado' => 1],
            ['id' => 7, 'tipo' => 'C', 'codigo' => '07', 'descripcion' => 'Devolución por ítem', 'estado' => 1],
            ['id' => 8, 'tipo' => 'C', 'codigo' => '08', 'descripcion' => 'Bonificación', 'estado' => 1],
            ['id' => 9, 'tipo' => 'C', 'codigo' => '09', 'descripcion' => 'Disminución en el valor', 'estado' => 1],
            ['id' => 10, 'tipo' => 'C', 'codigo' => '10', 'descripcion' => 'Otros Conceptos', 'estado' => 1],
            ['id' => 11, 'tipo' => 'C', 'codigo' => '11', 'descripcion' => 'Ajustes de operaciones de exportación', 'estado' => 1],
            ['id' => 12, 'tipo' => 'C', 'codigo' => '12', 'descripcion' => 'Ajustes afectos al IVAP', 'estado' => 1],
            ['id' => 13, 'tipo' => 'D', 'codigo' => '01', 'descripcion' => 'Intereses por mora', 'estado' => 1],
            ['id' => 14, 'tipo' => 'D', 'codigo' => '02', 'descripcion' => 'Aumento en el valor', 'estado' => 1],
            ['id' => 15, 'tipo' => 'D', 'codigo' => '03', 'descripcion' => 'Penalidades/ otros conceptos', 'estado' => 1],
            ['id' => 16, 'tipo' => 'D', 'codigo' => '10', 'descripcion' => 'Ajustes de operaciones de exportación', 'estado' => 1],
            ['id' => 17, 'tipo' => 'D', 'codigo' => '11', 'descripcion' => 'Ajustes afectos al IVAP', 'estado' => 1],
            ['id' => 18, 'tipo' => 'T', 'codigo' => '01', 'descripcion' => 'Venta', 'estado' => 1],
            ['id' => 19, 'tipo' => 'T', 'codigo' => '02', 'descripcion' => 'Compra', 'estado' => 1],
            ['id' => 20, 'tipo' => 'T', 'codigo' => '04', 'descripcion' => 'Traslado entre establecimientos de la misma empresa', 'estado' => 1],
            ['id' => 21, 'tipo' => 'T', 'codigo' => '08', 'descripcion' => 'Importación', 'estado' => 1],
            ['id' => 22, 'tipo' => 'T', 'codigo' => '09', 'descripcion' => 'Exportación', 'estado' => 1],
            ['id' => 23, 'tipo' => 'T', 'codigo' => '13', 'descripcion' => 'Otros', 'estado' => 1],
            ['id' => 24, 'tipo' => 'T', 'codigo' => '14', 'descripcion' => 'Venta sujeta a confirmación del comprador', 'estado' => 1],
            ['id' => 25, 'tipo' => 'T', 'codigo' => '18', 'descripcion' => 'Traslado emisor itinerante CP', 'estado' => 1],
            ['id' => 26, 'tipo' => 'T', 'codigo' => '19', 'descripcion' => 'Traslado a zona primaria', 'estado' => 1],
            ['id' => 27, 'tipo' => 'R', 'codigo' => '01', 'descripcion' => 'Transporte público', 'estado' => 1],
            ['id' => 28, 'tipo' => 'R', 'codigo' => '02', 'descripcion' => 'Transporte privado', 'estado' => 1],
        ];

        DB::table('tabla_parametrica')->insert($motivos);
    }
}
