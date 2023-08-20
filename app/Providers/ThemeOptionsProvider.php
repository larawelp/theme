<?php

namespace App\Providers;

use LaraWelP\Foundation\Support\Wp\Providers\ThemeOptionsProvider as ProvidersThemeOptionsProvider;

class ThemeOptionsProvider extends ProvidersThemeOptionsProvider
{
    public function boot(): void
    {
        parent::boot();
    }

    public function register(): void
    {
        //
    }
}
