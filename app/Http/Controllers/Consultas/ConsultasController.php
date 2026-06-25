<?php

namespace App\Http\Controllers\Consultas;

use App\Enums\Categorylevel;
use App\Models\Logistica\Categorias;
use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\listDetailProdRes;
use App\Http\Resources\Shop\ListProdshopReosurce;
use App\Models\Catalogos\TipoAfectacion;
use App\Models\Catalogos\TipoComprobante;
use App\Models\Catalogos\Unidades;
use App\Models\Logistica\Marcas;
use App\Models\Warehouse\Inventario;
use App\services\Consultas\FindStore;
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
        // Retorna todas las categorías activas con jerarquía
        $categorias = Categorias::whereNull('deleted_at')
            ->where('is_active', true)
            ->get()
            ->map(fn($c) => [
                "id"        => $c->id,
                "slug"      => $c->slug,
                "nombre"    => $c->name,
                "level"     => $c->level?->value,
                "parent_id" => $c->parent_id,
                "childrenCount" => $c->children()
                    ->whereNull('deleted_at')
                    ->where('is_active', true)
                    ->count(),
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


    public function listInventarioVirtual(Request $request)
    {
        $perPage = $request->integer('per_page', 12);

        $inventario = Inventario::with([
            'producto.imagenPrincipal',
            'almacen'
        ])
            ->whereHas('almacen', fn($q) => $q->where('tipo', 'VIRTUAL'))
            ->whereHas('producto', fn($q) => $q->where('destacado', true))
            ->paginate($perPage);

        return response()->json([
            'data' => ListProdshopReosurce::collection($inventario),
            'meta' => [
                'current_page' => $inventario->currentPage(),
                'last_page'    => $inventario->lastPage(),
                'per_page'     => $inventario->perPage(),
                'total'        => $inventario->total(),
            ],
        ]);
    }


    public function productosPorCategoria(Request $request)
    {
        $categoriaId = $request->query('categoria_id');
        $marcaId = $request->query('marca_id');
        $perPage = $request->integer('per_page', 12);

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
                function ($q) use ($categoriaId) {
                    // Buscar la categoría para ver si es padre
                    $cat = Categorias::find($categoriaId);
                    if ($cat && $cat->level === Categorylevel::CATEGORIA) {
                        // Si es categoría padre, incluir también sus subcategorías
                        $subIds = $cat->children()
                            ->whereNull('deleted_at')
                            ->where('is_active', true)
                            ->pluck('id')
                            ->toArray();
                        $allIds = array_merge([$categoriaId], $subIds);
                        $q->whereIn('categoria_id', $allIds);
                    } else {
                        $q->where('categoria_id', $categoriaId);
                    }
                }
            );
        }

        if ($marcaId) {
            $query->whereHas(
                'producto',
                fn($q) =>
                $q->where('marca_id', $marcaId)
            );
        }

        $inventario = $query->paginate($perPage);

        return response()->json([
            'data' => ListProdshopReosurce::collection($inventario),
            'meta' => [
                'current_page' => $inventario->currentPage(),
                'last_page'    => $inventario->lastPage(),
                'per_page'     => $inventario->perPage(),
                'total'        => $inventario->total(),
            ],
        ]);
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

    public function show($slug)
    {
        $producto = Inventario::with([
            'producto.imagenes',
            'producto.categoria',
            'producto.unidad',
            'producto.caracteristicas',
            'producto.presentaciones',

        ])
            ->whereHas('producto', fn($q) => $q->where('slug', $slug))
            ->orWhereHas('producto', fn($q) => $q->where('id', $slug))
            ->first();

        if (! $producto) {
            return response()->json([
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        return new listDetailProdRes($producto);
    }
}
