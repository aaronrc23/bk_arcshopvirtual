<?php

namespace App\Http\Resources\Administracion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class listSeries extends JsonResource
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
            'serie' => $this->serie,
            'correlativo' => $this->correlativo,
            'codigo' => $this->codigo,
            'sucursal_id' => $this->sucursal_id,
            'tipo_comprobante_id' => $this->tipo_comprobante_id,
            'comprobante' => $this->tipocomprobante->descripcion,
            'estado' => $this->estado,

        ];
    }
}
