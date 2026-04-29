<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controlador per gestionar l'establiment d'una nova contrasenya.
 * 
 * Gestiona la visualització del formulari de reinici (amb el token)
 * i el processament del canvi de contrasenya a la base de dades.
 * 
 * @package App\Http\Controllers
 * @subpackage Auth
 */
class NewPasswordController extends Controller
{
    /**
     * Mostra la vista de reinici de contrasenya.
     * 
     * @param Request $request Petició amb el token de reinici.
     * @return View Vista del formulari per introduir la nova contrasenya.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Gestiona la petició de nova contrasenya.
     * 
     * @param Request $request Petició amb les dades del reinici i el token.
     * @return RedirectResponse Redirecció al login si té èxit, o de tornada amb errors.
     * @throws ValidationException Si la validació de les dades falla.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Aquí intentarem restablir la contrasenya de l'usuari. Si té èxit, actualitzarem
        // la contrasenya en un model d'usuari real i la persistirem a la base de dades.
        // En cas contrari, analitzarem l'error i retornarem la resposta.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Si la contrasenya es va restablir correctament, redirigirem l'usuari a la
        // vista d'inici autenticada de l'aplicació. Si hi ha un error, podem
        // redirigir-lo a on venia amb el seu missatge d'error.
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
