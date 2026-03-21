<?php

namespace App\Http\Resources\Logistica\Productos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListProd extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'precio_venta' => $this->precio_venta,
            'precio_compra' => $this->precio_compra,
            'precio_mayoreo' => $this->precio_mayoreo,
            'codigo_barras' => $this->codigo_barras,
            'codigo_interno' => $this->codigo_interno,
            'afecto_icbper' => $this->afecto_icbper,
            'factor_icbper' => $this->factor_icbper,
            'tipo_afectacion' => [
                'id' => $this->tipo_afectacion_id,
                'descripcion' => $this->tipoAfectacion->descripcion,
            ],
            'categoria' => [
                'id' => $this->categoria_id,
                'name' => $this->categoria->name,
            ],
            'unidad_id' => $this->unidad_id,
            'activo' => $this->activo,
            'destacado' => $this->destacado,
            'cantidad_mayoreo' => $this->cantidad_mayoreo,
            'imagenes' => ProductImgResource::collection($this->imagenes),
        ];
    }
}
