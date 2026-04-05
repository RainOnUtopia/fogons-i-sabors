<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Actualitza la contrasenya de l'usuari.
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
