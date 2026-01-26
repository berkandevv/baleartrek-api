<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
        // Usuario que se está actualizando:
        // - Si la ruta tiene {user}, se usa ese
        // - Si no, se trata del usuario autenticado (/user)
        $user = $this->route('user') ?? $this->user();

        // Permitimos updates parciales: cada campo es opcional con "sometimes".
        return [
            // Datos personales
            'name' => ['sometimes', 'string', 'max:255'],
            'lastname' => ['sometimes', 'string', 'max:255'],
            // DNI único, ignorando el propio usuario
            'dni' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users', 'dni')->ignore($user->id),
            ],
            // Email único, ignorando el propio usuario
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['sometimes', 'string', 'max:255'],

            // Campos sensibles que no deben modificarse desde este endpoint genérico
            'role_id' => ['prohibited'],
            'password' => ['prohibited'],
        ];
    }
}
