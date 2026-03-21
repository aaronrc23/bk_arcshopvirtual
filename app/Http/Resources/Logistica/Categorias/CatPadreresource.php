<?php

namespace App\Http\Resources\Logistica\Categorias;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatPadreresource extends JsonResource
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
            'icon' => $this->icon,
            'order' => $this->order,
        ];
    }
}
