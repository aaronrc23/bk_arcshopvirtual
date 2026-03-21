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

            'producto' => [
                'id' => $this->producto->id,
                'name' => $this->producto->name,
                'precio' => $this->producto->precio_venta,

                'imagen' => $this->producto->imagenPrincipal
                    ? Storage::disk('public')->url($this->producto->imagenPrincipal->url)
                    : null,
            ],
        ];
    }
}
