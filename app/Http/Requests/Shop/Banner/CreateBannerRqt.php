<?php

namespace App\Http\Requests\Shop\Banner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateBannerRqt extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bannerId = $this->route('id');

        return [
            'titulo' => ['nullable', 'string', 'max:255'],
            'subtitulo' => ['nullable', 'string', 'max:500'],
            'enlace' => ['nullable', 'string', 'max:500'],
            'orden' => ['nullable', 'integer', 'min:0', 'max:999'],

            // 🔥 Imagen: obligatoria en creación, opcional en edición
            'imagen' => [
                $bannerId ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5MB
                'dimensions:max_width=4000,max_height=2000',
            ],


        ];
    }

    public function messages(): array
    {
        return [
            'imagen.required' => 'Debe seleccionar una imagen para el banner',
            'imagen.image' => 'El archivo debe ser una imagen válida',
            'imagen.mimes' => 'La imagen debe estar en formato: jpg, jpeg, png o webp',
            'imagen.max' => 'La imagen no debe superar los 5MB',
            'imagen.dimensions' => 'La imagen no debe superar los 4000×2000 píxeles',
            'titulo.max' => 'El título no debe exceder los 255 caracteres',
            'subtitulo.max' => 'El subtítulo no debe exceder los 500 caracteres',
            'enlace.max' => 'El enlace no debe exceder los 500 caracteres',
        ];
    }
}
