<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RecipeController;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredRecipe = Recipe::orderBy('rating', 'desc')->first();
    return view('welcome', compact('featuredRecipe'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// RECEPTES: la ruta /create ha d'anar ABANS del wildcard {recipe}
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

Route::middleware('auth')->group(function () {
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::post('/recipes/{recipe}/favorite', [FavoriteController::class, 'store'])->name('recipes.favorite.store');
    Route::delete('/recipes/{recipe}/favorite', [FavoriteController::class, 'destroy'])->name('recipes.favorite.destroy');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::patch('/profile/about', [ProfileController::class, 'updateAbout'])->name('profile.about.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// El wildcard {recipe} va ÚLTIM per no capturar /create
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
});
