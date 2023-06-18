<?php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Process as Definitions;
use App\Nayra\Contracts\Bpmn\ActivityInterface;
use App\Nayra\Contracts\Bpmn\TokenInterface;
use App\Nayra\Contracts\Engine\ExecutionInstanceInterface;

final class CompleteActivity extends BpmnAction implements ShouldQueue
{

    public $definitionsId;
    public $instanceId;
    public $tokenId;

    /**
     * Create a new job instance.
     *
     * @return void
     * @param mixed[] $data
     */
    public function __construct(Definitions $definitions, ExecutionInstanceInterface $instance, TokenInterface $token, public array $data)
    {
        $this->definitionsId = $definitions->getKey();
        $this->instanceId = $instance->getKey();
        $this->tokenId = $token->getKey();
    }

    /**
     * Execute the job.
     */
    public function action(TokenInterface $token, ActivityInterface $element, array $data): void
    {
        $dataStore = $token->getInstance()->getDataStore();
        //@todo requires a class to manage the data access and control the updates
        foreach ($data as $key => $value) {
            $dataStore->putData($key, $value);
        }

        $element->complete($token);
    }
}
