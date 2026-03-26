<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'El nom és obligatori.',
            'name.string' => 'El nom no té un format vàlid.',
            'name.max' => 'El nom no pot superar els 255 caràcters.',

            'email.required' => 'El correu electrònic és obligatori.',
            'email.string' => 'El correu electrònic no té un format vàlid.',
            'email.lowercase' => 'El correu electrònic s\'ha d\'escriure en minúscules.',
            'email.email' => 'Introdueix un correu electrònic vàlid.',
            'email.max' => 'El correu electrònic no pot superar els 255 caràcters.',
            'email.unique' => 'Aquest correu electrònic ja està registrat.',

            'password.required' => 'Has d\'introduir una contrasenya.',
            'password.confirmed' => 'La confirmació de la contrasenya no coincideix. Escriu la mateixa contrasenya als dos camps.',
            'password.min' => 'La contrasenya ha de tenir almenys :min caràcters.',
            'password.letters' => 'La contrasenya ha d\'incloure almenys una lletra.',
            'password.mixed' => 'La contrasenya ha d\'incloure majúscules i minúscules.',
            'password.numbers' => 'La contrasenya ha d\'incloure almenys un número.',
            'password.symbols' => 'La contrasenya ha d\'incloure almenys un símbol.',
            'password.uncompromised' => 'Aquesta contrasenya no és segura. Prova\'n una altra.',
        ], [
            'name' => 'nom',
            'email' => 'correu electrònic',
            'password' => 'contrasenya',
            'password_confirmation' => 'confirmació de la contrasenya',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }
}
