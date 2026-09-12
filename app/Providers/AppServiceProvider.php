<?php

namespace App\Providers;

use Carbon\Carbon;
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
     * Set locale Carbon ke Bahasa Indonesia agar translatedFormat bekerja.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
    }
}
