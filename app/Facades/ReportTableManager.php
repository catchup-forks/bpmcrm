<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ReportTableManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'report_table.manager';
    }
}
