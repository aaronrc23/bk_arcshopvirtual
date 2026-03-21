<?php

namespace App\Http\Requests\Administracion\Asignacion;

use Illuminate\Foundation\Http\FormRequest;

class AssignPerModuleRqt extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'modulos' => ['required', 'array', 'min:1'],
            'modulos.*' => ['string'],
        ];
    }
}
