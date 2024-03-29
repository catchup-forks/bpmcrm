<?php

namespace App\Managers;

use Throwable;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Model\Task;
use App\Model\Process;

final class TaskManager
{

    /**
     * Get a list of All Task in a process.
     *
     * @param Process $process
     *
     */
    public function index(Process $process, array $options): LengthAwarePaginator
    {
        $query = Task::where('process_id', $process->id);
        $filter = $options['filter'];
        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $query->where(function ($query) use ($filter): void {
                $query->Where('title', 'like', $filter)
                    ->orWhere('description', 'like', $filter);
            });
        }
        return $query->orderBy($options['sort_by'], $options['sort_order'])
            ->paginate($options['per_page'])
            ->appends($options);
    }

    /**
     * Create a new Task in a process.
     *
     * @param Process $process
     * @param array $data
     *
     * @return Task
     * @throws Throwable
     */
    public function save(Process $process, $data): Task
    {
        $data['process_id'] = $process->id;

        $task = new Task();
        $task->fill($data);
        $task->saveOrFail();

        return $task->refresh();
    }

    /**
     * Update Task in a process.
     *
     * @param Process $process
     * @param Task $task
     * @param array $data
     *
     * @return Task
     * @throws Throwable
     */
    public function update(Process $process, Task $task, $data): Task
    {
        $data['process_id'] = $process->id;
        $task->fill($data);

        $task->saveOrFail();
        return $task->refresh();
    }

    /**
     * Remove Task in a process.
     *
     * @param Task $task
     *
     * @throws Exception
     */
    public function remove(Task $task): ?bool
    {
        return $task->delete();
    }

}
