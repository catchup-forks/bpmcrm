<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for our Database Manager
 * @package app\Facades
 * @see \app\Managers\DatabaseManager
 */
class DatabaseManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'database.manager';
    }
}
