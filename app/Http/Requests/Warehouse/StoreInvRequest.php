<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvRequest extends FormRequest
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
            'product_id' => 'required|exists:productos,id',
            'almacen_id'  => 'required|exists:almacenes,id',

            'stock'    => 'required|numeric|min:0',

            'min_stock'   => 'nullable|integer|min:0',
            'max_stock'   => 'nullable|integer|gte:min_stock',

            'estado'      => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.exists' => 'El producto no existe',
            'almacen_id.exists'  => 'El almacén no existe',
            'max_stock.gte'      => 'El stock máximo debe ser mayor o igual al mínimo',
        ];
    }
}
