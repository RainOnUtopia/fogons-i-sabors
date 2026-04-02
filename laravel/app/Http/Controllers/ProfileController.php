<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controlador responsable de la gestió del perfil d'usuari autenticat.
 * Permet veure, editar i eliminar el compte de l'usuari actual.
 */
class ProfileController extends Controller
{
    /**
     * Mostra la pàgina pública del perfil de l'usuari.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $favoriteRecipes = collect();

        // Carregam els plats favorits sense N+1 i nomÃ©s si la taula pivot existeix.
        if (Schema::hasTable('favorites')) {
            $user->load('favoriteRecipes');
            $favoriteRecipes = $user->favoriteRecipes;
        }

        return view('profile.show', [
            'user' => $user,
            'favoriteRecipes' => $favoriteRecipes,
        ]);
    }

    /**
     * Mostra el formulari d'edició del perfil de l'usuari.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualitza la informació del perfil de l'usuari autenticat.
     * Si l'email canvia, es requereix nova verificació.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Actualitza la imatge de perfil de l'usuari autenticat.
     * Només permet fitxers PNG i JPG/JPEG.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('avatarUpload', [
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $validated['avatar']->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return Redirect::route('profile.show')->with('status', 'avatar-updated');
    }

    /**
     * Actualitza la descripció personal de l'usuari (Sobre mi).
     */
    public function updateAbout(Request $request): RedirectResponse
    {
        $request->validate([
            'about_me' => ['nullable', 'string', 'max:500'],
        ]);

        $request->user()->update(['about_me' => $request->about_me]);

        return Redirect::route('profile.show')->with('status', 'about-updated');
    }

    /**
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
