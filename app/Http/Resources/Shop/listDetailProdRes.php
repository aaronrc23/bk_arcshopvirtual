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
            'id' => $this->id ?? null,
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
                'caracteristicas' => $this->producto->caracteristicas->map(function ($item) {
                    return [
                        'descripcion' => $item->descripcion,
                    ];
                }),

                'presentaciones' => $this->producto->presentaciones->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'medida' => $item->medida,
                        'unidades_por_caja' => $item->unidades_por_caja,
                        'peso' => $item->peso,
                        'dimensiones' => [
                            'largo' => $item->largo,
                            'ancho' => $item->ancho,
                            'alto' => $item->alto,
                        ],
                        'es_principal' => (bool) $item->es_principal,
                    ];
                }),
            ],
        ];
    }
}
