<?php

namespace App\services\Logistica\Productos;

use App\Models\Logistica\Productos;

class ListProductoServices
{
    public function findAll($producto = null)
    {
        $productos = Productos::whereNull('deleted_at')
            ->where('activo', true)
            ->where('name', 'like', "%{$producto}%")
            ->orderBy('name', 'asc')
            ->with('categoria', 'unidad', 'tipoAfectacion')
            ->get();
        return $productos;
    }
}
