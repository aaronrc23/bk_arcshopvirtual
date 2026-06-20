<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FooterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'razon_social' => $this->razon_social,
            'nombre_comercial' => $this->nombre_comercial,
            'descripcion' => $this->descripcion,
            'direccion' => $this->direccion,
            'departamento' => $this->departamento,
            'provincia' => $this->provincia,
            'distrito' => $this->distrito,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url' => $this->twitter_url,
            'whatsapp' => $this->whatsapp,
            'footer_links' => $this->footer_links,
            'copyright_text' => $this->copyright_text,
            'logo_url' => $this->logo_path
                ? Storage::disk('public')->url($this->logo_path)
                : null,
        ];
    }
}
