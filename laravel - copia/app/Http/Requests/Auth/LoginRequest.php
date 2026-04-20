<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determina si l'usuari esta autoritzat a fer aquesta petició.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obté les regles de validació que s'apliquen a la petició.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Obté els missatges de validació per a les regles definides.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correu electrònic és obligatori.',
            'email.string' => 'El correu electrònic no té un format vàlid.',
            'email.email' => 'Introdueix un correu electrònic vàlid.',
            'password.required' => 'Has d\'introduir la contrasenya.',
            'password.string' => 'La contrasenya no té un format vàlid.',
        ];
    }

    /**
     * Obté els atributs personalitzats per als errors del validació.
     */
    public function attributes(): array
    {
        return [
            'email' => 'correu electrònic',
            'password' => 'contrasenya',
        ];
    }

    /**
     * Intenta autenticar les credencials de la petició.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Les credencials no són correctes. Revisa el correu electrònic i la contrasenya.',
            ]);
        }

        if (!Auth::user()->is_active) {
            Auth::logout();

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'El teu compte està desactivat. Contacta amb un administrador.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Assegura que la petició d'inici de sessió no estigui subjecta a limitació de taxa.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Has superat el nombre màxim d\'intents. Torna-ho a provar d\'aquí a ' . $seconds . ' segons.',
        ]);
    }

    /**
     * Obté la clau de limitació de taxa per a la petició.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
