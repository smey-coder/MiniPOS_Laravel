<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Check if Gate class exists
        if (class_exists(Gate::class)) {
            Gate::before(function ($user, $ability) {
                if ($user && method_exists($user, 'hasRole')) {
                    return $user->hasRole('superadmin') ? true : null;
                }
            });
        }
    }
}
