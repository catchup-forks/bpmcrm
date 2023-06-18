<?php
namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ProcessCollaboration;
use App\Models\ProcessRequest as Instance;
use App\Nayra\Contracts\Bpmn\ParticipantInterface;
use App\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use App\Nayra\Contracts\Repositories\ExecutionInstanceRepositoryInterface;
use App\Nayra\Contracts\Repositories\StorageInterface;
use App\Nayra\RepositoryTrait;

/**
 * Execution Instance Repository.
 *
 * @package app\Models
 */
final class ExecutionInstanceRepository implements ExecutionInstanceRepositoryInterface
{

    use RepositoryTrait;

    /**
     * Create an execution instance.
     *
     * @return ExecutionInstanceInterface
     */

    public function createExecutionInstance()
    {
        $instance = new Instance();
        $instance->setId(uniqid('request', true));
        return $instance;
    }

    /**
     * Load an execution instance from a persistent storage.
     *
     * @param string $instanceId
     *
     * @return \app\Nayra\Contracts\Engine\ExecutionInstanceInterface
     */
    public function loadExecutionInstanceByUid($instanceId, StorageInterface $storage)
    {
        $instance = Instance::find($instanceId);
        if (!$instance) {
            abort(404, 'Instance not found');
        }
        $callableId = $instance->callable_id;
        $process = $storage->getProcess($callableId);
        $dataStore = $storage->getFactory()->createDataStore();
        $dataStore->setData($instance->data);
        $instance->setProcess($process);
        $instance->setDataStore($dataStore);
        $process->getTransitions($storage->getFactory());

        //Load tokens:
        foreach ($instance->tokens as $token) {
            $tokenInfo = [
                'id' => $token->getKey(),
                'status' => $token->status,
                'element_ref' => $token->element_id,
            ];
            $token->setProperties($tokenInfo);
            $element = $storage->getElementInstanceById($tokenInfo['element_ref']);
            $element->addToken($instance, $token);
        }
        return $instance;
    }

    /**
     * Create or update an execution instance to a persistent storage.
     *
     * @param \app\Nayra\Contracts\Engine\ExecutionInstanceInterface $instance
     *
     * @return $this
     */
    public function storeExecutionInstance(ExecutionInstanceInterface $instance): void
    {
        // TODO: Implement store() method. or Remove from Interface
    }

    /**
     * Persists instance data related to the event Process Instance Created
     *
     * @param ExecutionInstanceInterface $instance
     *
     * @return mixed
     */
    public function persistInstanceCreated(ExecutionInstanceInterface $instance): void
    {
        //Get instance data
        $data = $instance->getDataStore()->getData();
        //Get the process
        $process = $instance->getProcess();
        //Get process definition
        $definition = $process->getEngine()->getProcess();

        //Save the row
        $instance->callable_id = $process->getId();
        $instance->process_id = $definition->getKey();
        $instance->user_id = Auth::user()->getKey();
        $instance->name = $definition->name;
        $instance->status = 'ACTIVE';
        $instance->initiated_at = Carbon::now();
        $instance->data = $data;
        $instance->saveOrFail();
        $instance->setId($instance->getKey());
    }

    /**
     * Persists instance data related to the event Process Instance Completed
     *
     * @param ExecutionInstanceInterface $instance
     *
     * @return mixed
     */
    public function persistInstanceUpdated(ExecutionInstanceInterface $instance): void
    {
        //Get instance data
        $data = $instance->getDataStore()->getData();
        //Save instance
        $instance->status = 'ACTIVE';
        $instance->data = $data;
        $instance->saveOrFail();
    }

    /**
     * Persists instance data related to the event Process Instance Completed
     *
     * @param ExecutionInstanceInterface $instance
     *
     * @return mixed
     */
    public function persistInstanceCompleted(ExecutionInstanceInterface $instance): void
    {
        //Get instance data
        $data = $instance->getDataStore()->getData();
        //Save instance
        $instance->status = 'COMPLETED';
        $instance->completed_at = Carbon::now();
        $instance->data = $data;
        $instance->saveOrFail();
    }

    /**
     * Persists collaboration between two instances.
     *
     * @param ExecutionInstanceInterface $instance Target instance
     * @param ParticipantInterface $participant Participant related to the target instance
     * @param ExecutionInstanceInterface $source Source instance
     * @param ParticipantInterface $sourceParticipant
     */
    public function persistInstanceCollaboration(ExecutionInstanceInterface $instance, ParticipantInterface $participant, ExecutionInstanceInterface $source, ParticipantInterface $sourceParticipant): void
    {
        if ($source->process_collaboration_id === null) {
            $collaboration = new ProcessCollaboration();
            $collaboration->process_id = $instance->process->getKey();
            $collaboration->saveOrFail();
            $source->process_collaboration_id = $collaboration->getKey();
            $source->saveOrFail();
        }
        $instance->process_collaboration_id = $source->process_collaboration_id;
        $instance->participant_id = $participant ? $participant->getId() : null;
        $instance->saveOrFail();
    }
}
