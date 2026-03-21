<?php

namespace App\Http\Controllers\Consultas;

use App\Enums\Categorylevel;
use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\listDetailProdRes;
use App\Http\Resources\Shop\ListProdshopReosurce;
use App\Models\Catalogos\TipoAfectacion;
use App\Models\Catalogos\TipoComprobante;
use App\Models\Catalogos\Unidades;
use App\Models\Logistica\Categorias;
use App\Models\Warehouse\Inventario;
use Illuminate\Http\Request;


class ConsultasController extends Controller
{


    public function refprod()
    {
        $unidad = Unidades::select('id', 'descripcion')->get();
        $afectacion = TipoAfectacion::all();
        $categoria = Categorias::where('level', Categorylevel::SUBCATEGORIA)->select('id', 'name')->get();

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


    public function listInventarioVirtual()
    {
        $inventario = Inventario::with([
            'producto.imagenPrincipal',
            'almacen'
        ])
            ->whereHas('almacen', fn($q) => $q->where('tipo', 'VIRTUAL'))
            ->get();

        return ListProdshopReosurce::collection($inventario);
    }

    public function show($id)
    {
        $producto = Inventario::with([
            'producto.imagenes', // 🔥 todas las imágenes
            'producto.categoria',
            'producto.unidad'
        ])
            ->where('product_id', $id)
            ->firstOrFail();

        return new listDetailProdRes($producto);
    }
}
