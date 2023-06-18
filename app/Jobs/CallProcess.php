<?php
namespace App\Jobs;

use App\Model\Process as Definitions;
use App\Nayra\Contracts\Bpmn\ProcessInterface;

final class CallProcess extends BpmnAction
{

    public $definitionsId;
    public $processId;

    /**
     * Create a new job instance.
     *
     * @return void
     * @param mixed[] $data
     */
    public function __construct(Definitions $definitions, ProcessInterface $process, public array $data)
    {
        $this->definitionsId = $definitions->id;
        $this->processId = $process->getId();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function action(ProcessInterface $process)
    {
        //Create an initial data store for the process instance
        $dataStorage = $process->getRepository()->createDataStore();
        $dataStorage->setData($this->data);

        //Call the process
        $instance = $process->call($dataStorage);
        return $instance;
    }
}
