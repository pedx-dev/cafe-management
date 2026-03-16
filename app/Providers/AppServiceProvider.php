<?php

namespace App\Providers;

use App\Services\Courier\CourierProviderManager;
use App\Services\Courier\FastTrackCourierProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CourierProviderManager::class, function () {
            $manager = new CourierProviderManager();
            $manager->register(new FastTrackCourierProvider());

            return $manager;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        RateLimiter::for('integration-callback', function (Request $request) {
            return [
                Limit::perMinute(30)->by((string) $request->ip()),
            ];
        });

        RateLimiter::for('integration-send-order', function (Request $request) {
            return [
                Limit::perMinute(20)->by((string) $request->ip()),
            ];
        });
    }
}
