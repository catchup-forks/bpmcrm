<?php

namespace App\Facades;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;
use App\Model\Task;
use App\Model\TaskUser;

/**
 * Facade for our Task Manager
 *
 * @package app\Facades
 * @see \app\Managers\TaskAssigneeManager
 *
 * @method static array saveAssignee(Task $task , array $options)
 * @method static Paginator|LengthAwarePaginator loadAssignees(Task $task, array $options)
 * @method static Paginator|LengthAwarePaginator loadAvailable(Task $activity, array $options)
 * @method static void removeAssignee(Task $activity, string $assignee)
 * @method static TaskUser getInformationAssignee(Task $activity, string $assignee)
 * @method static Paginator getInformationAllAssignee(Task $activity, array $options)
 *
 */
final class TaskAssigneeManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'task_assignee.manager';
    }
}
