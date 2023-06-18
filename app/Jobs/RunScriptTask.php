<?php
namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Process as Definitions;
use App\Models\Script;
use App\Nayra\Contracts\Bpmn\ScriptTaskInterface;
use App\Nayra\Contracts\Bpmn\TokenInterface;
use App\Nayra\Contracts\Engine\ExecutionInstanceInterface;

final class RunScriptTask extends BpmnAction implements ShouldQueue
{

    public $definitionsId;
    public $instanceId;
    public $tokenId;

    /**
     * Create a new job instance.
     *
     * @param \app\Models\Process $definitions
     * @param \app\Nayra\Contracts\Engine\ExecutionInstanceInterface $instance
     * @param \app\Nayra\Contracts\Bpmn\TokenInterface $token
     * @param mixed[] $data
     */
    public function __construct(Definitions $definitions, ExecutionInstanceInterface $instance, TokenInterface $token, public array $data)
    {
        $this->definitionsId = $definitions->getKey();
        $this->instanceId = $instance->getKey();
        $this->tokenId = $token->getKey();
    }

    /**
     * Execute the script task.
     */
    public function action(TokenInterface $token, ScriptTaskInterface $element): void
    {
        $scriptRef = $element->getProperty('scriptRef');
        Log::info('Script started: ' . $scriptRef);
        $configuration = json_decode((string) $element->getProperty('config'), true, 512, JSON_THROW_ON_ERROR);

        // Check to see if we've failed parsing.  If so, let's convert to empty array.
        if ($configuration === null) {
            $configuration = [];
        }
        $dataStore = $token->getInstance()->getDataStore();
        $data = $dataStore->getData();
        if (empty($scriptRef)) {
            $script = new Script([
                'code' => $element->getScript(),
                'language' => Script::scriptFormat2Language($element->getProperty('scriptFormat', 'application/x-php'))
            ]);
        } else {
            $script = Script::find($scriptRef);
        }

        $response = $script->runScript($data, $configuration);
        if (is_array($response['output'])) {
            foreach ($response['output'] as $key => $value) {
                $dataStore->putData($key, $value);
            }
            $element->complete($token);
            Log::info('Script completed: ' . $scriptRef);
        } else {
            $token->setStatus(ScriptTaskInterface::TOKEN_STATE_FAILING);
            Log::info('Script failed: ' . $scriptRef . ' - ' . $response['output']);
        }
    }
}
