<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use LaraWelP\Foundation\Support\Providers\EventServiceProvider as ProvidersEventServiceProvider;

class EventServiceProvider extends ProvidersEventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register the WordPress actions
     * @var array
     */
    protected $action = [
        'pre_get_posts' => 'App\Listeners\MainQueryListener'
    ];

    /**
     * Register the WordPress filters
     * @var array
     */
    protected $filter = [
        //'the_content' => ['App\Listeners\theContentListener']
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        //
    }
}
