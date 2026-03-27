<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // Fix Livewire asset loading in sub-directories (Local Laragon/XAMPP)
        $baseUrl = config('app.url');
        if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/FinanceSW/public')) {
             \Illuminate\Support\Facades\Config::set('livewire.asset_url', '/FinanceSW/public');
        }

        if (str_contains($baseUrl, 'https://')) {
            URL::forceScheme('https');
        }
    }
}
