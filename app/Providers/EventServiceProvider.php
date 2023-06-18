<?php
namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Register our Events and their Listeners
 * @package app\Providers
 */
final class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * @var array
     */
    protected $listen = [
        Login::class => [
            'ProcessMaker\Listeners\LoginListener',
        ],
    ];
}
