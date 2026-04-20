<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determina si l'usuari està autoritzat a fer aquesta petició.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obté les regles de validació que s'apliquen a la petició.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * Obté els missatges de validació personalitzats.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nom és obligatori.',
            'email.required' => 'El correu electrònic és obligatori.',
            'email.email' => 'El format del correu electrònic no és vàlid.',
            'message.required' => 'El missatge és obligatori.',
            'message.min' => 'El missatge ha de tenir almenys 10 caràcters.',
        ];
    }
}
