<?php

namespace App\Http\Resources\Logistica\Productos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresentacionResource extends JsonResource
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
            'medida' => $this->medida,
            'unidades_por_caja' => $this->unidades_por_caja,
            'largo' => $this->largo,
            'ancho' => $this->ancho,
            'alto' => $this->alto,
            'peso' => $this->peso,
            'es_principal' => (bool) $this->es_principal,
        ];
    }
}
