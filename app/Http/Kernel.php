<?php

namespace App\Http;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Http\Middleware\GenerateMenus;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use App\Http\Middleware\Authorize;
use App\Http\Middleware\RedirectIfAuthenticated;
use app\Http\Middleware\PermissionCheck;
use Illuminate\Routing\Middleware\ThrottleRequests;
use App\Http\Middleware\SetLanguage;
use App\Http\Middleware\SetSkin;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

final class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        TrimStrings::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
    ];
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,   // In case we want to log users out after changing password, we need this
            ShareErrorsFromSession::class,
            //\app\Http\Middleware\VerifyCsrfToken::class,         // This is disabled until all routes are handled by our new engine
            SubstituteBindings::class,
            GenerateMenus::class,
            CreateFreshApiToken::class,


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
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'authorize' => Authorize::class,
        'bindings' => SubstituteBindings::class,
        'guest' => RedirectIfAuthenticated::class,
        'permission' => PermissionCheck::class,
        'throttle' => ThrottleRequests::class,
        'setlang' => SetLanguage::class,
        'setskin' => SetSkin::class,
        'session' => StartSession::class,
    ];
}
