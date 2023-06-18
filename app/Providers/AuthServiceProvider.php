<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

/**
 * Our AuthService Provider binds our base processmaker provider and registers any policies, if defined.
 * @package app\Providers
 * @todo Add gates to provide authorization functionality. See branch release/3.3 for sample implementations
 */
final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Passport::routes();
    }

}
