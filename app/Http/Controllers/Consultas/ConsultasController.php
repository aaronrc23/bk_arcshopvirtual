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
use App\Models\Logistica\Marcas;
use App\Models\Warehouse\Inventario;
use App\Services\Consultas\FindStore;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{
    protected FindStore $findStore;
    public function __construct(FindStore $findStore)
    {
        $this->findStore = $findStore;
    }


    public function listCatpadre()
    {
        return $this->findStore->findCategorias();
    }
    public function listCategorias()
    {
        $categorias = Categorias::where("level", Categorylevel::SUBCATEGORIA)
            ->get()
            ->map(fn($c) => [
                "id" => $c->id,
                "nombre" => $c->name,
            ]);

        return response()->json($categorias);
    }

    public function listMarcas()
    {
        $marcas = Marcas::all()
            ->map(fn($m) => [
                "id" => $m->id,
                "nombre" => $m->nombre,
            ]);

        return response()->json($marcas);
    }

    public function refprod()
    {
        $unidad = Unidades::select('id', 'descripcion')->get();
        $afectacion = TipoAfectacion::all();
        $categoria = Categorias::where('level', Categorylevel::SUBCATEGORIA)->select('id', 'name')->get();
        $marcas = Marcas::all(['id', 'nombre']);
        return [
            'unidad' => $unidad,
            'afectacion' => $afectacion,
            'categoria' => $categoria,
            'marcas' => $marcas
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


    public function productosPorCategoria(Request $request)
    {
        $categoriaId = $request->query('categoria_id');
        $marcaId = $request->query('marca_id');

        $query = Inventario::with([
            'producto.imagenPrincipal',
            'producto.categoria',
            'producto.unidad',
            'almacen'
        ])
            ->whereHas('almacen', fn($q) => $q->where('tipo', 'VIRTUAL'));

        if ($categoriaId) {
            $query->whereHas(
                'producto',
                fn($q) =>
                $q->where('categoria_id', $categoriaId)
            );
        }

        if ($marcaId) {
            $query->whereHas(
                'producto',
                fn($q) =>
                $q->where('marca_id', $marcaId)
            );
        }

        return ListProdshopReosurce::collection($query->get());
    }

    // public function productosPorCategoria($categoriaId)
    // {
    //     $inventario = Inventario::with([
    //         'producto.imagenPrincipal',
    //         'producto.categoria',
    //         'producto.unidad',
    //         'almacen'
    //     ])
    //         ->whereHas('almacen', fn($q) => $q->where('tipo', 'VIRTUAL'))
    //         ->whereHas('producto', function ($q) use ($categoriaId) {
    //             $q->where('categoria_id', $categoriaId);
    //         })
    //         ->get();

    //     return ListProdshopReosurce::collection($inventario);
    // }

    public function show($id)
    {
        $producto = Inventario::with([
            'producto.imagenes',
            'producto.categoria',
            'producto.unidad',
            'producto.caracteristicas',
            'producto.presentaciones',

        ])
            ->where('product_id', $id)
            ->first();

        return new listDetailProdRes($producto);
    }
}
