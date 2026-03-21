<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpleadoCreateRqt extends FormRequest
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
            // Usuario
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => 'required|min:6',

            // Perfil
            'name' => 'required|string',
            'apellidos' => 'required|string',
            'direccion' => 'nullable|string',

            // Empleado
            'phone' => 'nullable|string',
            'dni' => [
                'nullable',
                'string',
                Rule::unique('empleados', 'dni')->whereNull('deleted_at'),
            ],

            'genero' => 'nullable|string',
            'codigo_postal' => 'nullable|string',
            'departamento' => 'nullable|string',
            'provincia' => 'nullable|string',
            'distrito' => 'nullable|string',
            'referencia' => 'nullable|string',
            'photo' => 'nullable|string',
        ];
    }
}
