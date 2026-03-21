<?php

namespace App\Services\Logistica\Categorias;

use App\Enums\CategoryLevel;
use App\Http\Resources\Logistica\CatDropResource;
use App\Models\Logistica\Categorias;
use Illuminate\Support\Facades\DB;

class ListCatService
{

    public function listCategoriasFull()
    {
        return Categorias::with('parentCategory')->get();
    }
    public function listCategoriasPadre()
    {
        $categorias = Categorias::whereNull('deleted_at')
            ->where('is_active', true)
            ->where('level', CategoryLevel::CATEGORIA)
            ->orderBy('order')
            ->get();

        return $categorias;
    }


    public function getCategoriasEstructura()
    {
        $categorias = Categorias::with(['children.children'])
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return $categorias
            ->where('level', CategoryLevel::CATEGORIA)
            ->whereNull('parent_id')
            ->map(function ($categoria) {
                return [
                    'id' => $categoria->id,
                    'nombre' => $categoria->name,
                    'icono' => $categoria->icon,
                    'subgrupos' => $categoria->children->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'titulo' => $sub->name,
                            'items' => $sub->children->map(fn($item) => [
                                'id' => $item->id,
                                'name' => $item->name,
                            ]),
                        ];
                    }),
                ];
            })->values();
    }

    public function findOne(int $id)
    {
        return Categorias::findOrFail($id);
    }

    public function categoriasHijas()
    {
        return DB::table('categorias as c')
            ->leftJoin('categorias as p', 'c.parent_id', '=', 'p.id')
            ->select(
                'c.id',
                'c.name',
                DB::raw('p.name as parentName')
            )
            ->whereNotNull('c.parent_id')
            ->whereNull('c.deleted_at')
            ->get();
    }




    /*----lISTAR Categorias Eliminadas */
    public function categoriasEliminadas()
    {
        $categorias = Categorias::onlyTrashed()
            ->with('parentCategory')
            ->get();

        if ($categorias->isEmpty()) {
            abort(400, 'No hay categorías eliminadas');
        }

        return CatDropResource::collection($categorias);
    }
}
