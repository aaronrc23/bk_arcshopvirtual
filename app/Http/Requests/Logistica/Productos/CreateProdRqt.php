<?php

namespace App\Http\Requests\Logistica\Productos;

use Illuminate\Foundation\Http\FormRequest;

class CreateProdRqt extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'precio_compra' => ['nullable', 'numeric', 'min:0'],
            'precio_mayoreo' => ['nullable', 'numeric', 'min:0'],
            'cantidad_mayoreo' => ['nullable', 'integer', 'min:1'],
            'tipo_afectacion_id' => ['required', 'exists:tipo_afectacion,id'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'unidad_id' => ['required', 'exists:unidades,id'],
            'afecto_icbper' => ['required', 'boolean'],
            'factor_icbper' => ['nullable', 'numeric'],
            'activo' => ['nullable', 'boolean'],
            'destacado' => ['nullable', 'boolean'],
            'imagenes' => ['nullable', 'array', 'max:7'],
            'imagenes.*' => [
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
        ];
    }
}
