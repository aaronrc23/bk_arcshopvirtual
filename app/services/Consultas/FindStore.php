<?php

namespace App\services\Consultas;

use App\Enums\Categorylevel;
use App\Models\Logistica\Categorias;
use Illuminate\Support\Facades\Storage;

class FindStore
{
    public function findCategorias()
    {
        $categoriasPadre = Categorias::whereNull('deleted_at')
            ->where('is_active', true)
            ->where('level', Categorylevel::CATEGORIA)
            ->orderBy('order')
            ->get();

        $result = $categoriasPadre->map(function ($categoria) {
            $childrenCount = $categoria->children()
                ->whereNull('deleted_at')
                ->where('is_active', true)
                ->count();

            return [
                'id'         => $categoria->id,
                'slug'       => $categoria->slug,
                'categoria'  => $categoria->name ?? '-',
                'img'        => $categoria->imagen && Storage::disk('public')->exists($categoria->imagen)
                    ? Storage::disk('public')->url($categoria->imagen)
                    : null,
                'icon'       => $categoria->icon,
                'childrenCount' => $childrenCount,
            ];
        });

        return response()->json($result ?? []);
    }
}
