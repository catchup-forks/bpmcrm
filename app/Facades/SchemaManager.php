<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

final class SchemaManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'schema.manager';
    }
}
