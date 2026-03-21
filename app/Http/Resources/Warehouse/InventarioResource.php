<?php

namespace App\Http\Resources\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventarioResource extends JsonResource
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

            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,
            'estado' => $this->estado,

            // 🔥 relaciones
            'producto' => [
                'id' => $this->producto?->id,
                'nombre' => $this->producto?->name,
                'precio' => $this->producto?->precio_venta,
            ],

            'almacen' => [
                'id' => $this->almacen?->id,
                'nombre' => $this->almacen?->nombre,
                'tipo' => $this->almacen?->tipo,
            ],
        ];
    }
}
