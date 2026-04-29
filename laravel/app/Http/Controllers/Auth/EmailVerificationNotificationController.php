<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controlador per gestionar l'enviament de notificacions de verificació de correu.
 * 
 * Permet a l'usuari sol·licitar un nou enllaç de verificació si el primer no ha arribat
 * o ha caducat.
 * 
 * @package App\Http\Controllers
 * @subpackage Auth
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Envia una nova notificació de verificació d'email.
     * 
     * @param Request $request Petició de l'usuari autenticat.
     * @return RedirectResponse Redirecció al dashboard (si ja està verificat) o enrere amb l'estat d'enviament.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
