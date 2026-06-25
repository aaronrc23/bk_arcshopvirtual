<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ListProdshopReosurce extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stock' => $this->stock,

            'producto' => $this->producto ? [
                'id' => $this->producto->id,
                'slug' => $this->producto->slug,
                'name' => $this->producto->name,
                'precio' => $this->producto->precio_venta,
                'precio_mayoreo' => $this->producto->precio_mayoreo,
                'cantidad_mayoreo' => $this->producto->cantidad_mayoreo,

                'imagen' => $this->producto->imagenPrincipal
                    ? Storage::disk('public')->url($this->producto->imagenPrincipal->url)
                    : null,

            ] : null,
        ];
    }
}
