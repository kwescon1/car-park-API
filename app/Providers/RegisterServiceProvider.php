<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RegisterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(\App\Services\AuthService\AuthServiceInterface::class, \App\Services\AuthService\AuthService::class);
        $this->app->bind(\App\Services\ParkingService\ParkingServiceInterface::class, \App\Services\ParkingService\ParkingService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
