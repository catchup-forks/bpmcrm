<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

/**
 * Provides a simple middleware layer to do a permission check against a user
 */
final class PermissionCheck
{
    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct(
        /**
         * The authentication factory instance.
         */
        private readonly Auth $auth
    )
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  string  $ability
     * @param  array|null  $models
     * @return mixed
     *
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        $this->auth->authenticate();

        return $next($request);
    }

}
