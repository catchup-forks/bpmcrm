<?php
namespace App\Jobs;

use App\Models\Process as Definitions;
use App\Nayra\Contracts\Bpmn\ProcessInterface;
use App\Nayra\Contracts\Bpmn\StartEventInterface;

final class StartEvent extends BpmnAction
{

    public $definitionsId;
    public $processId;
    public $elementId;

    /**
     * Create a new job instance.
     *
     * @return void
     * @param mixed[] $data
     */
    public function __construct(Definitions $definitions, StartEventInterface $event, public array $data)
    {
        $this->definitionsId = $definitions->getKey();
        $this->processId = $event->getOwnerProcess()->getId();
        $this->elementId = $event->getId();
    }

    /**
     * Start a $process from start event $element.
     *
     * @return \app\Nayra\Contracts\Engine\ExecutionInstanceInterface
     */
    public function action(ProcessInterface $process, StartEventInterface $element)
    {
        //Create a new data store
        $dataStorage = $process->getRepository()->createDataStore();
        $dataStorage->setData($this->data);
        $instance = $process->getEngine()->createExecutionInstance($process, $dataStorage);
        $element->start();
        
        //Return the instance created
        return $instance;
    }
}
