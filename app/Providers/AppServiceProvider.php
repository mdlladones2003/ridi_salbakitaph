<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
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
        // 🌍 Global HTTP macros for APIs
        Http::macro('osm', function () {
            return Http::withHeaders([
                'User-Agent' => 'RidiSalbaKita/1.0 (localhost; https://ridisalbakita.ph; contact@ridisalbakita.ph)',
                'Accept-Language' => 'en',
            ])->timeout(15);
        });

        Http::macro('geoapify', function () {
            return Http::withHeaders([
                'User-Agent' => 'RidiSalbaKita/1.0 (localhost; https://ridisalbakita.ph; contact@ridisalbakita.ph)',
                'Accept-Language' => 'en',
            ])->timeout(15);
        });

        Http::macro('ridi', function () {
            return Http::withHeaders([
                'User-Agent' => config('app.user_agent', 'RidiSalbaKita/1.0 (https://ridisalbakita.ph; contact@ridisalbakita.ph)'),
            ]);
        });
    }
}
