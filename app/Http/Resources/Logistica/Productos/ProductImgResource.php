<?php

namespace App\Http\Resources\Logistica\Productos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductImgResource extends JsonResource
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
            'url' => $this->url
                ? Storage::disk('public')->url($this->url)
                : null,
            'orden' => $this->orden,
            'is_principal' => (bool) $this->is_principal,
        ];
    }
}
