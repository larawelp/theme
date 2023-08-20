<?php

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Str;

remove_filter('template_redirect', 'redirect_canonical');

if (defined('ARTISAN_BINARY')) {
    return;
}


define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__ . '/vendor/autoload.php';


/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/bootstrap/app.php';


/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$kernel->handle($request = Illuminate\Http\Request::capture());

$app['events']->listen(RequestHandled::class, function (RequestHandled $event) use ($kernel) {
    if ($event->request->fullUrlIs('*.js')) {
        return;
    }

    if ($event->request->getPathInfo() == '/_debugbar/assets/javascript') {
        $event->response->header('Content-Type', 'application/javascript');
    }


    foreach($event->response->headers->all() as $key => $value) {
        if($key === 'set-cookie') {
            continue;
        }
        header_remove($key);
    }

    $event->response->send();

    $kernel->terminate($event->request, $event->response);
});

function get_comment_author_link_blank($comment_ID = 0)
{
    $comment = get_comment($comment_ID);
    $url = get_comment_author_url($comment);
    $author = get_comment_author($comment);

    if (empty($url) || 'http://' === $url) {
        $return = $author;
    } else {
        $return = "<a target=\"_blank\" href='$url' rel='external nofollow ugc' class='url'>$author</a>";
    }

    /**
     * Filters the comment author's link for display.
     *
     * @param string $return The HTML-formatted comment author link.
     *                           Empty for an invalid URL.
     * @param string $author The comment author's username.
     * @param int $comment_ID The comment ID.
     *
     * @since 4.1.0 The `$author` and `$comment_ID` parameters were added.
     *
     * @since 1.5.0
     */
    return apply_filters('get_comment_author_link', $return, $author, $comment->comment_ID);
}
