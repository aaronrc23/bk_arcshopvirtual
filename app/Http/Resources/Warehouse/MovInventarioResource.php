<?php

namespace App\Http\Resources\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovInventarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'tipo' => $this->tipo,
            'cantidad' => $this->cantidad,

            'stock_anterior' => $this->stock_anterior,

            // si luego agregas stock_nuevo en DB
            'stock_nuevo' => $this->stock_nuevo ?? null,

            'descripcion' => $this->descripcion,

            'fecha' => $this->created_at?->format('Y-m-d H:i:s'),

            'usuario' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->profile->name ?? null,
                'email' => $this->user->email,
            ] : null,

            'inventario' => $this->inventario ? [
                'id' => $this->inventario->id,
                'stock' => $this->inventario->stock,

                'producto' => $this->inventario->producto ? [
                    'id' => $this->inventario->producto->id,
                    'nombre' => $this->inventario->producto->name,
                    'sku' => $this->inventario->producto->codigo_interno ?? null,
                ] : null,

                'almacen' => $this->inventario->almacen ? [
                    'id' => $this->inventario->almacen->id,
                    'nombre' => $this->inventario->almacen->nombre,
                ] : null,
            ] : null,
        ];
    }
}
