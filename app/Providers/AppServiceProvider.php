<?php

namespace App\Providers;

use App\Filament\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(BaseNotification::class,Notification::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Generous, intelligent rate limits:
        // In local development: no limits (Limit::none()) to prevent blocking developer/testing.
        // In production: allows 30 auth attempts/min and 180 API calls/min, avoiding shared Wi-Fi/CGNAT lockouts.
        RateLimiter::for('quiz-auth', function (Request $request) {
            if (app()->environment('local')) {
                return Limit::none();
            }
            $key = (string) ($request->input('phone') ?? $request->input('email') ?? $request->input('identifier') ?? $request->ip());
            return Limit::perMinute(30)->by($key . '|' . $request->ip());
        });

        RateLimiter::for('quiz-api', function (Request $request) {
            if (app()->environment('local')) {
                return Limit::none();
            }
            return Limit::perMinute(180)->by($request->ip());
        });
    }
}
