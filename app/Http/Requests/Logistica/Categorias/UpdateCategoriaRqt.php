<?php

namespace App\Http\Requests\Logistica\Categorias;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaRqt extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:categorias,id'],
            'icon' => ['nullable', 'string', 'max:255'],
            'level' => ['nullable', 'string'],
            'removeImage' => ['nullable', 'boolean'],
            'imagen' => ['nullable', 'image', 'max:2048'],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'parent_id' => in_array($this->parent_id, ['null', ''], true)
                ? null
                : $this->parent_id,
        ]);
    }
}
