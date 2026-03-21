<?php

namespace App\Http\Requests\Warehouse;

use App\Enums\TipoAlm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
class CreateAlmRqt extends FormRequest
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
            'code' => ['nullable', 'string'],
            'nombre' => ['required', 'string'],
            'tipo' => ['required', new Enum(TipoAlm::class)],
            'is_principal' => ['nullable', 'boolean'],
        ];
    }
}
