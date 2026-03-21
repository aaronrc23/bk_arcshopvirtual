<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tipocomp = [
            [
                'id' => 1,
                'codigo' => '01',
                'descripcion' => 'FACTURA',
            ],
            [
                'id' => 2,
                'codigo' => '03',
                'descripcion' => 'BOLETA',
            ],
            [
                'id' => 3,
                'codigo' => '07',
                'descripcion' => 'NOTA DE CREDITO',
            ],
            [
                'id' => 4,
                'codigo' => '08',
                'descripcion' => 'NOTA DE DEBITO',
            ],
            [
                'id' => 5,
                'codigo' => '09',
                'descripcion' => 'GUIA DE REMISION',
            ],
            [
                'id' => 6,
                'codigo' => '31',
                'descripcion' => 'GUIA DE TRANSPORTISTA',
            ],
            [
                'id' => 7,
                'codigo' => 'RA',
                'descripcion' => 'RESUMENES DE ANULACIONES',
            ],
            [
                'id' => 8,
                'codigo' => 'RC',
                'descripcion' => 'RESUMENES DE COMPROBANTES',
            ]

        ];
        DB::table('tipo_comprobante')->insert($tipocomp);

        $tipo_afectacion = [
            [
                'id' => '10',
                'nombre' => 'IGV',
                'descripcion' => 'OP.GRAVADAS',
                'letra' => 'S',
                'codigo' => '1000',
                'tipo' => 'VAT'
            ],
            [
                'id' => '20',
                'nombre' => 'EXO',
                'descripcion' => 'OP.EXONERADAS',
                'letra' => 'E',
                'codigo' => '9997',
                'tipo' => 'VAT'
            ],
            [
                'id' => '30',
                'nombre' => 'INA',
                'descripcion' => 'OP.INAFECTAS',
                'letra' => '0',
                'codigo' => '9998',
                'tipo' => 'FRE'
            ],
        ];

        DB::table('tipo_afectacion')->insert($tipo_afectacion);
    }
}
