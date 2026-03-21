<?php

namespace App\Repositories\Warehouse;

use App\Enums\TipoAlm;
use App\Models\Warehouse\Inventario;
use Illuminate\Support\Collection;

class InventarioRepository
{
    public function create(array $data, $manager = null)
    {
        return Inventario::create([
            ...$data,
            'stock' => $data['stock'] ?? 0,
            'minStock' => $data['minStock'] ?? 1,
            'estado' => true,
        ]);
    }

    public function findByProductAndAlmacen(int $productId, int $almacenId): ?Inventario
    {
        return Inventario::where('product_id', $productId)
            ->where('almacen_id', $almacenId)
            ->first();
    }

    public function listarVirtuales(): Collection
    {
        return Inventario::with([
            'almacen',
            'product.categoria',
            'product.unidad'
        ])
            ->whereHas(
                'almacen',
                fn($q) =>
                $q->where('tipo', TipoAlm::VIRTUAL)
            )
            ->whereHas(
                'product.categoria',
                fn($q) =>
                $q->where('isActive', true)
            )
            ->latest()
            ->get();
    }

    public function listarPorAlmacen(int $almacenId): Collection
    {
        return Inventario::with([
            'product.categoria',
            'product.unidad',
            'almacen'
        ])
            ->where('almacen_id', $almacenId)
            ->get();
    }

    public function buscarPorNombre(string $nombre, int $almacenId): Collection
    {
        return Inventario::with([
            'product.categoria',
            'product.unidad',
            'almacen'
        ])
            ->where('almacen_id', $almacenId)
            ->whereHas(
                'product',
                fn($q) =>
                $q->where('name', 'LIKE', "%{$nombre}%")
            )
            ->get();
    }
}
