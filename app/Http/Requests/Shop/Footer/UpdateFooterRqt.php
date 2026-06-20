<?php

namespace App\Http\Requests\Shop\Footer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFooterRqt extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'departamento' => ['nullable', 'string', 'max:100'],
            'provincia' => ['nullable', 'string', 'max:100'],
            'distrito' => ['nullable', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'twitter_url' => ['nullable', 'url', 'max:500'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'copyright_text' => ['nullable', 'string', 'max:500'],
            'razon_social' => ['nullable', 'string', 'max:100'],
            'nombre_comercial' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'facebook_url.url' => 'La URL de Facebook no es válida',
            'instagram_url.url' => 'La URL de Instagram no es válida',
            'twitter_url.url' => 'La URL de Twitter no es válida',
            'email.email' => 'El correo electrónico no es válido',
            'descripcion.max' => 'La descripción no debe exceder los 1000 caracteres',
            'copyright_text.max' => 'El texto de copyright no debe exceder los 500 caracteres',
        ];
    }
}
