<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class CorsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $origins = $this->getAllowedOrigins();
            Config::set('cors.allowed_origins', $origins);
        });
    }

    protected function getAllowedOrigins(): array
    {
        $origins = [];

        if (app()->environment('development')) {
            $origins = [
                'https://doba.test',
                'http://localhost:3000',
                'http://127.0.0.1:3000',
            ];
        }

        if (app()->environment('production')) {
            $productionOrigins = config('app.cors_allowed_origins');
            if ($productionOrigins) {
                $trimmedOrigins = array_map('trim', explode(',', $productionOrigins));
                $origins = array_merge($origins, $trimmedOrigins);
            }
        }

        return array_unique($origins);
    }
}
