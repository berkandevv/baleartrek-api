<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrekStoreRequest extends FormRequest
{
    /**
     * Solo admins pueden crear "treks".
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Reglas de validacion para crear un "trek" con meetings y comentarios.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Datos principales del "trek"
            'regNumber' => ['required', 'string', 'max:255', 'unique:treks,regnumber'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['sometimes', 'in:y,n'],

            // Municipio por nombre o por id (uno de los dos obligatorio)
            'municipality' => ['required_without:municipality_id', 'string', 'exists:municipalities,name'],
            'municipality_id' => ['required_without:municipality', 'integer', 'exists:municipalities,id'],

            // Meetings opcionales asociadas a la ruta
            'meetings' => ['sometimes', 'array'],
            'meetings.*.day' => ['required', 'date'],
            'meetings.*.time' => ['required', 'date_format:H:i:s'],
            'meetings.*.DNI' => ['required', 'exists:users,dni'],

            // Comentarios opcionales dentro de cada meeting
            'meetings.*.comments' => ['sometimes', 'array'],
            'meetings.*.comments.*.DNI' => ['required', 'exists:users,dni'],
            'meetings.*.comments.*.comment' => ['required', 'string'],
            'meetings.*.comments.*.score' => ['required', 'integer', 'between:0,5'],
            'meetings.*.comments.*.status' => ['sometimes', 'in:y,n'],
        ];
    }
}
