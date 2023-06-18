<?php

namespace App\Managers;

use Throwable;
use Illuminate\Support\Facades\Validator;
use App\Exception\ValidationException;
use App\Model\Process;

/**
 * This class provides methods to manage de processes.
 *
 * @package app\Managers
 */
class ProcessManager
{

    /**
     * Stores a new process.
     *
     * @param $data
     *
     * @return Process
     * @throws Throwable
     */
    public function store($data): Process
    {
        $process = new Process();
        $process->fill($data);
        if (isset($data['bpmn'])) {
            $process->bpmn = $data['bpmn'];
        }

        $process->saveOrFail();

        $process->refresh();
        $process->category = $process->category()->first();
        $process->user = $process->user()->first();

        return $process;
    }

    /**
     * Update a process.
     *
     * @param Process $process
     * @param array $data
     *
     * @return Process
     * @throws Throwable
     */
    public function update(Process $process, $data): Process
    {
        $this->validate(
            [
                'process' => $process,
            ],
            [
            ]
        );
        $process->fill($data);
        $process->saveOrFail();
        return $process->refresh();
    }

    /**
     * Remove a process.
     *
     * @param Process $process
     *
     * @return bool|null
     * @throws ValidationException
     */
    public function remove(Process $process): ?bool
    {
        $this->validate(
            [
                'process' => $process,
            ],
            [
            ]
        );
        return $process->delete();
    }

    /**
     * Validate the given data with the given rules.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @throws ValidationException
     */
    private function validate(
        array $data,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    )
    {
        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
