<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Model\Process;

/**
 * Facade for the Process Manager
 *
 * @package app\Facades
 * @see \app\Managers\ProcessManager
 *
 * @method array index($filter, $start, $limit)
 * @method static Process store(array $data)
 *
 */
class ProcessManager extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'process.manager';
    }
}
