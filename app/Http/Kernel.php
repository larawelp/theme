<?php

namespace App\Http;

use App\Http\Middleware\LoginWordpressUser;
use App\Providers\CustomFieldsServiceProvider;
use App\Providers\CustomPostTypesProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Facade;
use LaraWelP\Foundation\Http\Kernel as HttpKernel;
use Exception;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Session\Middleware\StartSession::class,
        LoginWordpressUser::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\ShareViewData::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
        CustomPostTypesProvider::class,
        CustomFieldsServiceProvider::class,
    ];

    public function handle($request)
    {
        if (defined('ARTISAN_BINARY')) {
            return parent::handle($request);
        }

        $request->enableHttpMethodParameterOverride();
        $this->sendRequestThroughRouter($request);

        return $this;
    }

    /**
     * Send the given request through the middleware / router.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|void
     */
    protected function sendRequestThroughRouter($request)
    {
        if (defined('ARTISAN_BINARY')) {
            return parent::sendRequestThroughRouter($request);
        }

        $this->app->instance('request', $request);

        Facade::clearResolvedInstance('request');

        $this->bootstrap();

        // If administration panel is attempting to be displayed,
        // we don't need any response
        if (is_admin()) {
            return;
        }

        // Get response on `template_include` filter so the conditional functions work correctly
        add_filter(
            'template_include',
            function ($template) use ($request) {
                // If the template is not index.php, then don't output anything
                if ($template !== get_template_directory() . '/index.php') {
                    return $template;
                }

                $this->syncMiddlewareToWpRouter();

                try {
                    $response = (new Pipeline($this->app))
                        ->send($request)
                        ->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)
                        ->then($this->dispatchToRouter());
                } catch (Exception $e) {
                    $this->reportException($e);

                    $response = $this->renderException($request, $e);
                }

                $this->app['events']->dispatch(new RequestHandled($request, $response));

                return $template;
            },
            PHP_INT_MAX - 10
        );
    }

    protected function syncMiddlewareToWpRouter()
    {
        $originalRouter = $this->router;
        $this->router = $this->app['wpRouter']->getRouter();
        parent::syncMiddlewareToRouter();
        $this->router = $originalRouter;
    }

    protected function renderException($request, \Throwable $e)
    {
        return $this->app[ExceptionHandler::class]->render($request, $e);
    }
}
