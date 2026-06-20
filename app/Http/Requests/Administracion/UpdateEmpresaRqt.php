<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRqt extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'razon_social' => 'nullable|string|max:100',
            'nombre_comercial' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
            'direccion' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'distrito' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'facebook_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'twitter_url' => 'nullable|url|max:500',
            'whatsapp' => 'nullable|string|max:20',
            'copyright_text' => 'nullable|string|max:500',
        ];
    }
}
