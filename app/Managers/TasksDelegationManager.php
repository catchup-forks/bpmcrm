<?php

namespace App\Managers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Model\Application;
use App\Model\Delegation;
use App\Model\Task;
use App\Model\User;

final class TasksDelegationManager
{
    /**
     * Get a list of All Output Documents in a process.
     *
     *
     */
    public function index(array $options): LengthAwarePaginator
    {
        $start = $options['current_page'];
        Paginator::currentPageResolver(fn() => $start);
        $include = $options['include'] ? explode(',', (string) $options['include']): [];
        $include = array_unique(['user', 'application', ...$include]);
        $query = Delegation::with($include);
        if (!empty($options['status'])) {
            $query = $query->where('thread_status', '=', $options['status']);
        }
        $filter = $options['filter'];
        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $user = new User();
            $task = new Task();
            $application = new Application();
            $query = Delegation::where(function ($q) use ($filter, $user): void {
                    $q->whereHas('user', function ($query) use ($filter, $user): void {
                        $query->where($user->getTable() . '.firstname', 'like', $filter)
                            ->orWhere($user->getTable() . '.lastname', 'like', $filter);
                    });
                })
                ->orWhere(function ($q) use ($filter, $task): void {
                    $q->whereHas('task', function ($query) use ($filter, $task): void {
                        $query->where($task->getTable() . '.title', 'like', $filter)
                            ->orWhere($task->getTable() . '.description', 'like', $filter);
                    });
                })
                ->orWhere(function ($q) use ($filter, $application): void {
                    $q->whereHas('application', function ($query) use ($filter, $application): void {
                        $query->where($application->getTable() . '.APP_TITLE', 'like', $filter)
                            ->orWhere($application->getTable() . '.APP_DESCRIPTION', 'like', $filter);
                    });
                });

        }
        return $query->orderBy($options['sort_by'], $options['sort_order'])
            ->paginate($options['per_page'])
            ->appends($options);
    }

    /**
     * Get a task delegation
     *
     * @param Task $task
     *
     * @return Delegation
     */
    public function show(Task $task): Delegation
    {
        return Delegation::where('task_id', $task->id)
            ->with('task', 'user', 'application')
            ->first();
    }


}
