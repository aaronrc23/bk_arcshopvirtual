<?php

namespace App\Http\Requests\Logistica\Categorias;

use App\Enums\CategoryLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateCatRqt extends FormRequest
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

            'parent_id' => ['nullable', 'integer', 'exists:categorias,id'],

            // ✅ valida enum, pero NO convierte
            'level' => ['nullable', new Enum(CategoryLevel::class)],

            'icon' => ['nullable', 'string', 'max:255'],

            'imagen' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }
}
