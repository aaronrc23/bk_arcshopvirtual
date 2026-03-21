<?php

namespace App\Http\Controllers\Consultas;

use App\Enums\CategoryLevel;
use App\Http\Controllers\Controller;
use App\Models\Catalogos\TipoAfectacion;
use App\Models\Catalogos\TipoComprobante;
use App\Models\Catalogos\Unidades;
use App\Models\Logistica\Categorias;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{


    public function refprod()
    {
        $unidad = Unidades::select('id', 'descripcion')->get();
        $afectacion = TipoAfectacion::all();
        $categoria = Categorias::where('level', CategoryLevel::SUBCATEGORIA)->select('id', 'name')->get();

        return [
            'unidad' => $unidad,
            'afectacion' => $afectacion,
            'categoria' => $categoria,
        ];
    }

    public function listTipComprobante()
    {
        return TipoComprobante::all();
    }
}
