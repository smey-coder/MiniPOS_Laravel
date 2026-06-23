<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Purchase_Detail;
use App\Observers\PurchaseDetailObserver;
use App\Observers\PurchaseObserver;
use App\Models\Purchase;

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
        Purchase_Detail::observe(PurchaseDetailObserver::class);
        Purchase::observe(PurchaseObserver::class);
    }

}
