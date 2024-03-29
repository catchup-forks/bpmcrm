<?php

namespace App\Facades;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;
use App\Model\Task;
use App\Model\Process;

/**
 * Facade for our OutPut Document Manager
 *
 * @package app\Facades
 * @see \app\Managers\TaskManager
 *
 * @method static Paginator index(Process $process, array $options)
 * @method static Task save(Process $process, array $data)
 * @method static array update(Process $process, Task $task, array $data)
 * @method static boolean|null remove(Task $task)
 *
 */
final class TaskManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'task.manager';
    }
}
