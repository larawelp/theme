<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;
use LaraWelP\Foundation\Events\WhenFolioRegisters;

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
        WhenFolioRegisters::provide([$this, 'registerFolioRoutes']);
    }

    public function registerFolioRoutes()
    {
        if (!file_exists(resource_path('views/pages'))) {
            return;
        }
        Folio::path(resource_path('views/pages'))->middleware([
            '*' => [
                //
            ],
        ]);
    }
}
