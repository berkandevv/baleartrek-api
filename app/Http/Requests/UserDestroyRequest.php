<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Usuario objetivo de la eliminación:
        // - En DELETE /users/{user} viene desde la ruta
        // - En DELETE /user se usa el usuario autenticado
        $target = $this->route('user') ?? $this->user();
        // Usuario autenticado que realiza la petición
        $me = $this->user();

        // Autorización:
        // - Debe haber usuario autenticado
        // - Debe ser admin o estar borrándose a sí mismo
        return $me && ($me->isAdmin() || $me->id === $target->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
