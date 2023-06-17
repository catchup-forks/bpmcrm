<?php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Process as Definitions;
use App\Models\Script;
use App\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use App\Nayra\Contracts\Bpmn\TokenInterface;
use App\Nayra\Contracts\Engine\ExecutionInstanceInterface;

class RunServiceTask extends BpmnAction implements ShouldQueue
{

    public $definitionsId;
    public $instanceId;
    public $tokenId;
    public $data;

    /**
     * Create a new job instance.
     * 
     * @param \app\Models\Process $definitions
     * @param \app\Nayra\Contracts\Engine\ExecutionInstanceInterface $instance
     * @param \app\Nayra\Contracts\Bpmn\TokenInterface $token
     * @param array $data
     */
    public function __construct(Definitions $definitions, ExecutionInstanceInterface $instance, TokenInterface $token, array $data)
    {
        $this->definitionsId = $definitions->getKey();
        $this->instanceId = $instance->getKey();
        $this->tokenId = $token->getKey();
        $this->data = $data;
    }

    /**
     * Execute the script task.
     *
     * @return void
     */
    public function action(TokenInterface $token, ServiceTaskInterface $element)
    {
        $implementation = $element->getImplementation();
        Log::info('Service task started: ' . $implementation);
        $configuration = json_decode($element->getProperty('config'), true);

        // Check to see if we've failed parsing.  If so, let's convert to empty array.
        if ($configuration === null) {
            $configuration = [];
        }
        $dataStore = $token->getInstance()->getDataStore();
        $data = $dataStore->getData();
        if (empty($implementation)) {
            $element->complete($token);
            Log::info('Service task not implemented: ' . $implementation);
        } else {
            $script = Script::where('key', $implementation)->firstOrFail();
        }

        $response = $script->runScript($data, $configuration);
        if (is_array($response['output'])) {
            foreach ($response['output'] as $key => $value) {
                $dataStore->putData($key, $value);
            }
            $element->complete($token);
            Log::info('Service task completed: ' . $implementation);
        } else {
            $token->setStatus(ServiceTaskInterface::TOKEN_STATE_FAILING);
            Log::info('Service task failed: ' . $implementation . ' - ' . $response['output']);
        }
    }
}
