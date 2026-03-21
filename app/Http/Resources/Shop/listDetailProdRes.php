<?php

namespace App\Http\Resources\Shop;

use App\Http\Resources\Logistica\Productos\ProductImgResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class listDetailProdRes extends JsonResource
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

            'producto' => [
                'id' => $this->producto->id,
                'name' => $this->producto->name,
                'precio' => $this->producto->precio_venta,
                'descripcion' => $this->producto->description,
                'precio_mayoreo' => $this->producto->precio_mayoreo,
                'categoria' => $this->producto->categoria->name,
                'unidad' => $this->producto->unidad->descripcion,

                'imagen' => $this->producto->imagenPrincipal
                    ? Storage::disk('public')->url($this->producto->imagenPrincipal->url)
                    : null,

                // 🔥 TODAS las imágenes (galería)
                'imagenes' => ProductImgResource::collection(
                    $this->producto->imagenes
                ),
            ],
        ];
    }
}
