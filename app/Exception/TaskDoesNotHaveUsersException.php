<?php
namespace App\Exception;

use Exception;

/**
 * The task does not have users to assign
 *
 */
final class TaskDoesNotHaveUsersException extends Exception
{

    /**
     * @param string $task
     */
    public function __construct($task)
    {
        parent::__construct(__('exceptions.TaskDoesNotHaveUsersException', ['task' => $task]));
    }
}
