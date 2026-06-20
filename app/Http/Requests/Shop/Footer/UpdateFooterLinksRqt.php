<?php

namespace App\Http\Requests\Shop\Footer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFooterLinksRqt extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'footer_links' => ['required', 'array'],
            'footer_links.tienda' => ['nullable', 'array'],
            'footer_links.tienda.*.label' => ['required', 'string', 'max:255'],
            'footer_links.tienda.*.href' => ['required', 'string', 'max:500'],
            'footer_links.ayuda' => ['nullable', 'array'],
            'footer_links.ayuda.*.label' => ['required', 'string', 'max:255'],
            'footer_links.ayuda.*.href' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'footer_links.required' => 'Los links del footer son obligatorios',
            'footer_links.array' => 'Los links del footer deben ser un array',
            'footer_links.tienda.*.label.required' => 'Cada link de tienda debe tener una etiqueta',
            'footer_links.tienda.*.href.required' => 'Cada link de tienda debe tener una URL',
            'footer_links.ayuda.*.label.required' => 'Cada link de ayuda debe tener una etiqueta',
            'footer_links.ayuda.*.href.required' => 'Cada link de ayuda debe tener una URL',
        ];
    }
}
