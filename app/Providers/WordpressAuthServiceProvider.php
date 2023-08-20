<?php

namespace App\Providers;

use App\Auth\EloquentWordpressUserProvider;
use App\Guard\WordpressGuard;
use App\Hashing\WordPressHasher;
use Hautelook\Phpass\PasswordHash;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class WordpressAuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        Auth::extend('wordpress', function ($app, $name, array $config) {
            return new WordpressGuard(
                callback: function (Request $request, UserProvider $provider) {
                    // WIP to make it work
                    return null;
                },
                request: $app['request'],
                provider: new EloquentWordpressUserProvider($app['wordpress-auth'], $config['model'])
            );
        });

        $this->app->singleton('wordpress-auth', function ($app) {
            $iteration_count = $app['config']->get('wordpress-auth.hash.iteration_count');
            $portable_hashes = $app['config']->get('wordpress-auth.hash.portable_hashes');

            $hasher = new PasswordHash($iteration_count, $portable_hashes);

            return new WordPressHasher($hasher);
        });

        Auth::provider('eloquent.wordpress', function ($app, array $config) {
            return new EloquentWordpressUserProvider($app['wordpress-auth'], $config['model']);
        });
    }
}
