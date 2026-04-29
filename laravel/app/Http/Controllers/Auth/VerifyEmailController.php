<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador per processar la verificació del correu electrònic.
 * 
 * Gestiona l'enllaç de verificació que l'usuari rep al seu correu.
 * Un cop clicat, marca l'usuari com a verificat i el redirigeix al dashboard.
 * 
 * @package App\Http\Controllers
 * @subpackage Auth
 */
class VerifyEmailController extends Controller
{
    /**
     * Marca l'adreça de correu electrònic de l'usuari autenticat com a verificada.
     * 
     * @param EmailVerificationRequest $request Petició de verificació de correu.
     * @return RedirectResponse Redirecció al dashboard amb el paràmetre 'verified'.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
