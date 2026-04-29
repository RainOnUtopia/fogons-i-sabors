<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controlador per gestionar la confirmació de la contrasenya de l'usuari.
 * 
 * S'utilitza quan l'usuari intenta accedir a una zona protegida que requereix
 * una re-autenticació recent amb la seva contrasenya.
 * 
 * @package App\Http\Controllers
 * @subpackage Auth
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Mostra la vista per confirmar la contrasenya.
     * 
     * @return View Vista del formulari de confirmació de contrasenya.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirma la contrasenya de l'usuari.
     * 
     * @param Request $request Petició amb la contrasenya entregada.
     * @return RedirectResponse Redirecció a la destinació original després de confirmar.
     * @throws ValidationException Si la contrasenya no és correcta.
     */
    public function store(Request $request): RedirectResponse
    {
        if (
            !Auth::guard('web')->validate([
                'email' => $request->user()->email,
                'password' => $request->password,
            ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
