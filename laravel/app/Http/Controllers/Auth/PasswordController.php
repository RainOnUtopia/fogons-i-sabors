<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controlador per gestionar el canvi de contrasenya de l'usuari autenticat.
 * 
 * Permet a l'usuari canviar la seva contrasenya actual per una de nova,
 * validant que coneix la contrasenya actual.
 * 
 * @package App\Http\Controllers
 * @subpackage Auth
 */
class PasswordController extends Controller
{
    /**
     * Actualitza la contrasenya de l'usuari.
     * 
     * @param Request $request Petició amb la contrasenya actual i la nova.
     * @return RedirectResponse Redirecció enrere amb l'estat d'actualització.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::min(8)->numbers(), 'confirmed'],
        ], [
            'password.min' => 'La contrasenya ha de tenir almenys :min caràcters.',
            'password.numbers' => 'La contrasenya ha d\'incloure almenys un número.',
            'password.confirmed' => 'La confirmació de la contrasenya no coincideix.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
