<?php

namespace App\Facades;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;
use App\Model\Delegation;
use App\Model\Task;

/**
 * Facade for our Task Delegation Manager
 *
 * @package app\Facades
 * @see \app\Managers\TasksDelegationManager
 *
 * @method static Delegation show(Task $task)
 * @method static Paginator index(array $options)
 *
 */
class TasksDelegationManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'task_delegation.manager';
    }
}
