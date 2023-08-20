<?php

namespace App\Providers;

use LaraWelP\Foundation\Support\Wp\Providers\WidgetProvider as ProvidersWidgetProvider;

class WidgetProvider extends ProvidersWidgetProvider
{
    /**
     * Array of Class names that will be passed to `register_widget()`
     * @type array
     */
    protected $widgets = [];

    /**
     * Array of arguments(array) passed to `register_sidebar()`
     * Usually you should give something like [ 'name' => 'Nice Sidebar', 'id' => 'nice_sidebar']
     * @type array
     */
    protected $widgetAreas = [
        [
            'name' => 'Theme Sidebar',
            'id' => 'theme_sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => "</div>\n",
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => "</h2>\n",
            'before_sidebar' => '',
            'after_sidebar' => '',
            'show_in_rest' => true,
        ],
    ];


    public function boot(): void
    {
        //

        parent::boot();
    }
}
