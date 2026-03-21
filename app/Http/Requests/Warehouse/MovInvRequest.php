<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class MovInvRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'almacen_id' => 'required|exists:almacenes,id',
            'tipo' => 'required|in:TipoEntrada::cases()->value',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'nullable|boolean',
        ];
    }
}
