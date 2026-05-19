<?php

namespace App\Services\Consultas;

use App\Models\Logistica\Categorias;
use Illuminate\Support\Facades\Storage;

class FindStore
{
    public function findCategorias()
    {
        $categorias = Categorias::all();
        $result = $categorias->map(function ($categoria) {
            return [
                'categoria' => $categoria->name ?? '-',
                'img' => $categoria->imagen && Storage::disk('public')->exists($categoria->imagen)
                    ? Storage::disk('public')->url($categoria->imagen)
                    : Storage::disk('public')->url('default.png'),
            ];
        });

        return response()->json($result ?? []);
    }
}
