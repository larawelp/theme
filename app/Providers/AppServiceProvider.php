<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!session_id() && !app()->runningInConsole() && !headers_sent()) {
            session_start();
        }
        add_action('w3tc_flush_all', function ($flush) {
            Artisan::call('cache:clear');
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
