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

        // Ordenacions vàlides per evitar injeccions SQL via paràmetre sort.
        $allowedSorts = ['created_at', 'average_rating', 'title'];
        $allowedFavSorts = ['favorites.created_at', 'recipes.average_rating', 'recipes.title'];

        // ── REBOST (receptes pròpies) ─────────────────────────────────────
        $rSearch = trim((string) $request->input('r_search', ''));
        $rDifficulty = $request->input('r_difficulty', 'tots');
        $rSort = $request->input('r_sort', 'created_at');
        $rDirection = $request->input('r_direction', 'desc');

        $userRecipes = $user->recipes()
            ->when($rSearch !== '', function ($q) use ($rSearch) {
                // Cerca per títol, nom del xef o ingredients (JSON_SEARCH).
                $q->where(function ($s) use ($rSearch) {
                    $s->where('title', 'like', "%{$rSearch}%")
                        ->orWhereRaw(
                            "JSON_SEARCH(ingredients, 'one', ?) IS NOT NULL",
                            ["%{$rSearch}%"]
                        );
                });
            })
            ->when($rDifficulty !== 'tots', fn($q) => $q->where('difficulty', $rDifficulty))
            ->orderBy(
                in_array($rSort, $allowedSorts) ? $rSort : 'created_at',
                $rDirection === 'asc' ? 'asc' : 'desc'
            )
            // Paginació independent de la pestanya de favorits.
            ->paginate(8, ['*'], 'r_page')
            ->withQueryString();

        // ── FAVORITS ──────────────────────────────────────────────────────
        $fSearch = trim((string) $request->input('f_search', ''));
        $fDifficulty = $request->input('f_difficulty', 'tots');
        $fSort = $request->input('f_sort', 'favorites.created_at');
        $fDirection = $request->input('f_direction', 'desc');
        $favoriteRecipes = collect();

        // Carregam els favorits amb query paginada i filtres; sense N+1 perquè
        // favoriteRecipes() és un BelongsToMany que genera un JOIN intern.
        if (Schema::hasTable('favorites')) {
            $favoriteRecipes = $user->favoriteRecipes()
                ->when($fSearch !== '', function ($q) use ($fSearch) {
                    $q->where(function ($s) use ($fSearch) {
                        $s->where('recipes.title', 'like', "%{$fSearch}%")
                            ->orWhere('recipes.chef_name', 'like', "%{$fSearch}%")
                            ->orWhereRaw(
                                "JSON_SEARCH(recipes.ingredients, 'one', ?) IS NOT NULL",
                                ["%{$fSearch}%"]
                            );
                    });
                })
                ->when($fDifficulty !== 'tots', fn($q) => $q->where('recipes.difficulty', $fDifficulty))
                ->orderBy(
                    in_array($fSort, $allowedFavSorts) ? $fSort : 'favorites.created_at',
                    $fDirection === 'asc' ? 'asc' : 'desc'
                )
                // Paginació independent de la pestanya del rebost.
                ->paginate(9, ['*'], 'f_page')
                ->withQueryString();
        }
        $commentCounts = $user->comments()->count();

        return view('profile.show', [
            'user' => $user,
            'userRecipes' => $userRecipes,
            'favoriteRecipes' => $favoriteRecipes,
            // Filtres actius passats a la vista per repoblar els controls.
            'rSearch' => $rSearch,
            'rDifficulty' => $rDifficulty,
            'rSort' => $rSort,
            'rDirection' => $rDirection,
            'fSearch' => $fSearch,
            'fDifficulty' => $fDifficulty,
            'fSort' => $fSort,
            'fDirection' => $fDirection,
            'commentCounts' => $commentCounts,
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
     * Elimina el compte de l'usuari autenticat del sistema.
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
