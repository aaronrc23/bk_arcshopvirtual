<?php

namespace App\Http\Requests\Warehouse;

use App\Enums\TipoEntrada;
use Illuminate\Validation\Rules\Enum;
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $tipo = $this->tipo;

            if ($tipo === 'TRANSFERENCIA') {

                if (!$this->filled('almacen_origen_id')) {
                    $validator->errors()->add('almacen_origen_id', 'Requerido para transferencia');
                }

                if (!$this->filled('almacen_destino_id')) {
                    $validator->errors()->add('almacen_destino_id', 'Requerido para transferencia');
                }
            } else {

                if (!$this->filled('almacen_id')) {
                    $validator->errors()->add('almacen_id', 'Almacén requerido');
                }
            }
        });
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
            'tipo' => ['required', new Enum(TipoEntrada::class)],
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'nullable|boolean',
            'almacen_id' => 'required_if:tipo,' . TipoEntrada::ENTRADA->value . ',' . TipoEntrada::SALIDA->value . ',' . TipoEntrada::VENTA->value . ',' . TipoEntrada::REPOSICION->value . ',' . TipoEntrada::AJUSTE->value,

            'almacen_origen_id' => 'required_if:tipo,' . TipoEntrada::TRANSFERENCIA->value,
            'almacen_destino_id' => 'required_if:tipo,' . TipoEntrada::TRANSFERENCIA->value,
        ];
    }
}
