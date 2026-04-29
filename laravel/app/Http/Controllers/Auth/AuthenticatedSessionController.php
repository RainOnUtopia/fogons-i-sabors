<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controlador per gestionar la sessió d'autenticació de l'usuari.
 * 
 * S'encarrega de mostrar el formulari de login, processar l'autenticació
 * i tancar la sessió de l'usuari.
 * 
 * @package App\Http\Controllers
 * @subpackage Auth
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Mostra la vista de login.
     * 
     * @return View Vista del formulari d'inici de sessió.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Gestiona la petició d'autenticació.
     * 
     * @param LoginRequest $request Petició amb les credencials del usuari.
     * @return RedirectResponse Redirecció a l'inici del lloc si l'autenticació és correcta.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * Destrueix la sessió d'autenticació.
     * 
     * @param Request $request Petició actual.
     * @return RedirectResponse Redirecció a la pàgina d'inici.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
