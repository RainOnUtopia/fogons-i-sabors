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
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
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

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
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

        if (defined(Password::class.'::RESET_THROTTLED') && $status === Password::RESET_THROTTLED) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Has demanat massa enllaços en poc temps. Espera uns minuts i torna-ho a provar.']);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'No s\'ha pogut enviar l\'enllaç de recuperació. Torna-ho a provar en uns minuts.']);
    }
}
