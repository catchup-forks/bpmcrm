<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

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
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
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
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,   // In case we want to log users out after changing password, we need this
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            //\app\Http\Middleware\VerifyCsrfToken::class,         // This is disabled until all routes are handled by our new engine
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\GenerateMenus::class,
            \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,


        ],
        'api' => [
            // Empty middleware for api
            // @todo Determine if we need throttling.  Currently it interrupts test suites
            // However, we haven't had a product decision on utilizing throttling or not
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
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'authorize' => \App\Http\Middleware\Authorize::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'permission' => \app\Http\Middleware\PermissionCheck::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'setlang' => \App\Http\Middleware\SetLanguage::class,
        'setskin' => \App\Http\Middleware\SetSkin::class,
        'session' => \Illuminate\Session\Middleware\StartSession::class,
    ];
}
