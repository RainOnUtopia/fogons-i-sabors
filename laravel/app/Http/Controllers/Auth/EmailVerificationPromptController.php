<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador per mostrar l'avís de verificació de correu electrònic.
 * 
 * Redirigeix l'usuari a la vista que indica que ha de verificar el seu correu
 * abans de poder continuar utilitzant l'aplicació.
 * 
 * @package App\Http\Controllers
 * @subpackage Auth
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * Mostra la vista del prompt de verificació d'email.
     * 
     * @param Request $request Petició de l'usuari autenticat.
     * @return RedirectResponse|View Redirecció al dashboard si ja està verificat, o la vista de verificació.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard', absolute: false))
            : view('auth.verify-email');
    }
}
