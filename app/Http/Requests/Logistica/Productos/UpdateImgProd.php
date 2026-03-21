<?php

namespace App\Http\Requests\Logistica\Productos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImgProd extends FormRequest
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
            // 🧩 Estructura de imágenes existentes
            'imagenes' => ['required', 'array', 'max:5'],

            'imagenes.*.id' => [
                'nullable',
                'integer',
                'exists:producto_imgs,id'
            ],

            'imagenes.*.orden' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],

            'imagenes.*.isPrincipal' => [
                'nullable',
                'boolean'
            ],

            // 🆕 Nuevas imágenes
            'newFiles' => ['nullable', 'array', 'max:5'],
            'newFiles.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'imagenes.required' => 'Debe enviar la estructura de imágenes',
            'imagenes.max' => 'Solo se permiten hasta 5 imágenes',
            'imagenes.*.orden.required' => 'Cada imagen debe tener un orden',
            'imagenes.*.orden.max' => 'El orden máximo permitido es 5',

            'newFiles.*.image' => 'El archivo debe ser una imagen válida',
            'newFiles.*.max' => 'Cada imagen debe pesar como máximo 2MB',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalizar booleanos (por si viene string "true"/"false")
        $imagenes = collect($this->imagenes)->map(function ($img) {
            return [
                ...$img,
                'isPrincipal' => isset($img['isPrincipal'])
                    ? filter_var($img['isPrincipal'], FILTER_VALIDATE_BOOLEAN)
                    : false,
            ];
        })->toArray();

        $this->merge([
            'imagenes' => $imagenes,
        ]);
    }

    /**
     * Validación adicional: solo una imagen principal
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $principales = collect($this->imagenes)
                ->where('isPrincipal', true)
                ->count();

            if ($principales > 1) {
                $validator->errors()->add(
                    'imagenes',
                    'Solo una imagen puede ser principal'
                );
            }
        });
    }
}
