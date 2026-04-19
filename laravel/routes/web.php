<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\DuelController;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredRecipe = Recipe::orderBy('average_rating', 'desc')->first();
    return view('welcome', compact('featuredRecipe'));
});

Route::get('/sobre-nosaltres', function () {
    return view('about');
})->name('about');

Route::get('/contacte', function () {
    return view('contact');
})->name('contact');

Route::get('/politica-privacitat', function () {
    return view('privacy');
})->name('privacy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// RECEPTES: la ruta /create ha d'anar ABANS del wildcard {recipe}
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

Route::middleware('auth')->group(function () {
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::patch('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::post('/recipes/{recipe}/favorite', [FavoriteController::class, 'store'])->name('recipes.favorite.store');
    Route::delete('/recipes/{recipe}/favorite', [FavoriteController::class, 'destroy'])->name('recipes.favorite.destroy');
    Route::post('/recipes/{recipe}/rate', [\App\Http\Controllers\RatingController::class, 'store'])->name('recipes.rate');

    Route::post('/recipes/{recipe}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('recipes.comments.store');
    Route::put('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-duels', [DuelController::class, 'userDuels'])->name('duels.user');
    Route::post('/duels', [DuelController::class, 'store'])->name('duels.store');
    Route::patch('/duels/{duel}/status', [DuelController::class, 'updateStatus'])->name('duels.status.update');
    Route::post('/duels/{duel}/vote', [DuelController::class, 'vote'])->name('duels.vote');
});

// El wildcard {recipe} va ÚLTIM per no capturar /create
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');


require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
});
