<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
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
        JsonResource::withoutwrapping();
        //
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Response::macro('success', function ($data, $message = null, $statusCode = \Illuminate\Http\Response::HTTP_OK) {
            return response()->json([
                'data' => $data ?: null,
                'message' => $message ?: null,
            ], $statusCode);
        });

        Response::macro('created', function ($data, $message = null) {
            return response()->json([

                'data' => $data,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_CREATED);
        });

        Response::macro('notfound', function ($error) {
            return response()->json([
                'error' => $error,
            ], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        });

        Response::macro('error', function ($error, $statusCode = \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR) {
            return response()->json([
                'error' => $error,
            ], $statusCode);
        });
    }
}
