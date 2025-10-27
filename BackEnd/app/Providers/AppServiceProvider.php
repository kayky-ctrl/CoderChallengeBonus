<?php

namespace App\Providers;

use App\Models\PrimordialDuck;
use App\Observers\PrimordialDuckObserver;
use Illuminate\Support\ServiceProvider;

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
        PrimordialDuck::observe(PrimordialDuckObserver::class);
    }
}
