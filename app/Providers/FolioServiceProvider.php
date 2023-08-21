<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;

class FolioServiceProvider extends ServiceProvider
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
        \Event::listen('register.folio', function () {
            if(!file_exists(resource_path('views/pages'))) {
                return;
            }
            Folio::path(resource_path('views/pages'))->middleware([
                '*' => [
                    //
                ],
            ]);
        });
    }
}
