<?php

namespace App\Managers;

use app\Models\ProcessRequest;
use Illuminate\Support\Facades\Log;
use App\Jobs\CallProcess;
use App\Jobs\CompleteActivity;
use App\Jobs\RunScriptTask;
use App\Jobs\RunServiceTask;
use App\Jobs\StartEvent;
use App\Models\Process as Definitions;
use App\Models\ProcessRequestToken as Token;
use App\Nayra\Contracts\Bpmn\ProcessInterface;
use App\Nayra\Contracts\Bpmn\ScriptTaskInterface;
use App\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use App\Nayra\Contracts\Bpmn\StartEventInterface;
use App\Nayra\Contracts\Bpmn\TokenInterface;
use App\Nayra\Contracts\Engine\ExecutionInstanceInterface;

final class WorkflowManager
{

    /**
     * Complete a task.
     *
     * @param ExecutionInstanceInterface $instance
     * @param TokenInterface $token
     */
    public function completeTask(Definitions $definitions, ExecutionInstanceInterface $instance, TokenInterface $token, array $data): void
    {
        CompleteActivity::dispatchNow($definitions, $instance, $token, $data);
    }

    /**
     * Trigger an start event and return the instance.
     *
     * @param StartEventInterface $event
     * @return ProcessRequest
     */
    public function triggerStartEvent(Definitions $definitions, StartEventInterface $event, array $data)
    {
        //@todo Validate user permissions
        //Schedule BPMN Action
        return StartEvent::dispatchNow($definitions, $event, $data);
    }

    /**
     * Start a process instance.
     *
     * @param ProcessInterface $process
     *
     * @return ProcessRequest
     */
    public function callProcess(Definitions $definitions, ProcessInterface $process, array $data)
    {
        //Validate user permissions
        //Validate BPMN rules
        //Log BPMN actions
        //Schedule BPMN Action
        return CallProcess::dispatchNow($definitions, $process, $data);
    }

    /**
     * Run a script task.
     *
     * @param ScriptTaskInterface $scriptTask
     */
    public function runScripTask(ScriptTaskInterface $scriptTask, Token $token): void
    {
        Log::info('Dispatch a script task: ' . $scriptTask->getId());
        $instance = $token->processRequest;
        $process = $instance->process;
        RunScriptTask::dispatch($process, $instance, $token, [])->delay(1);
    }

    /**
     * Run a service task.
     *
     * @param ServiceTaskInterface $serviceTask
     */
    public function runServiceTask(ServiceTaskInterface $serviceTask, Token $token): void
    {
        Log::info('Dispatch a service task: ' . $serviceTask->getId());
        $instance = $token->processRequest;
        $process = $instance->process;
        RunServiceTask::dispatch($process, $instance, $token, []);
    }
}
