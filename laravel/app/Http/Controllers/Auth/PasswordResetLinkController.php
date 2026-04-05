<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Mostra la vista de sol·licitud d'enllaç de reinici de contrasenya.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Gestiona la petició d'enllaç de reinici de contrasenya.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'El correu electrònic és obligatori.',
            'email.email' => 'Introdueix un correu electrònic vàlid.',
        ], [
            'email' => 'correu electrònic',
        ]);

        // Enviarem l'enllaç de reinici de contrasenya a aquest usuari. Un cop hàgim
        // intentat enviar l'enllaç, examinarem la resposta i veurem el missatge que
        // hem de mostrar a l'usuari. Finalment, enviarem una resposta adequada.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'T\'hem enviat un enllaç per restablir la contrasenya al teu correu electrònic.');
        }

        if ($status === Password::INVALID_USER) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'No hem trobat cap usuari amb aquest correu electrònic.']);
        }

        if (defined(Password::class . '::RESET_THROTTLED') && $status === Password::RESET_THROTTLED) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Has demanat massa enllaços en poc temps. Espera uns minuts i torna-ho a provar.']);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'No s\'ha pogut enviar l\'enllaç de recuperació. Torna-ho a provar en uns minuts.']);
    }
}
