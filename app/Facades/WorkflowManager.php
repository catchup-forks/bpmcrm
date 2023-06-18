<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \app\Managers\WorkflowManager
 * 
 * @method mixed callProcess($filename, $processId)
 * @method mixed triggerStartEvent($definitions, $event, array $data)
 * @method mixed runScripTask(\app\Nayra\Contracts\Bpmn\ScriptTaskInterface $scriptTask, Token $token)
 * @method mixed runServiceTask(\app\Nayra\Contracts\Bpmn\ServiceTaskInterface $serviceTask, Token $token)
 */
final class WorkflowManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'workflow.manager';
    }
}
