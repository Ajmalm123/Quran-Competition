<?php

namespace App\Providers;

use App\Filament\Notification;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification as BaseNotification;


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
        //
    }
}
