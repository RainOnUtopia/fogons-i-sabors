<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();
        
        \App\Models\Rating::observe(\App\Observers\RatingObserver::class);
        \App\Models\Favorite::observe(\App\Observers\FavoriteObserver::class);
    }
}
