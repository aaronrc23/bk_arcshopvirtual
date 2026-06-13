<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordEmp extends FormRequest
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
            'password' => 'required|current_password',
            'new_password' => 'required|min:8|different:password',
            'new_password_confirmation' => 'required|min:8|same:new_password',
        ];
    }

    public function messages(): array
    {
        return [
            'password.current_password' => 'La contraseña actual es incorrecta.',
            'new_password.different' => 'La nueva contraseña no puede ser igual a la contraseña actual.',
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
