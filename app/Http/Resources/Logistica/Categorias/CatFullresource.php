<?php

namespace App\Http\Resources\Logistica\Categorias;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CatFullresource extends JsonResource
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
            'level' => $this->level,
            'parent_id' => $this->parentCategory?->id,
            'parentCategoryName' => $this->parentCategory?->name,
            'icon' => $this->icon,
            'isActive' => $this->is_active,
            'imagen' => $this->imagen
                ? Storage::disk('public')->url($this->imagen)
                : null,
        ];
    }
}
