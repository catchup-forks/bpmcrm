<?php
namespace App\Repositories;

use Carbon\Carbon;
use App\Models\ProcessRequestToken as Token;
use App\Nayra\Contracts\Bpmn\ActivityInterface;
use App\Nayra\Contracts\Bpmn\CatchEventInterface;
use App\Nayra\Contracts\Bpmn\ScriptTaskInterface;
use App\Nayra\Contracts\Bpmn\TokenInterface;
use App\Nayra\Contracts\Repositories\TokenRepositoryInterface;
use App\Repositories\ExecutionInstanceRepository;

/**
 * Execution Instance Repository.
 *
 * @package app\Models
 */
final class TokenRepository implements TokenRepositoryInterface
{
    /**
     * Initialize the Token Repository.
     */
    public function __construct(private readonly ExecutionInstanceRepository $instanceRepository)
    {
    }

    /**
     * Creates an instance of Token.
     *
     * @return \app\Nayra\Contracts\Bpmn\TokenInterface
     */
    public function createTokenInstance(): TokenInterface
    {
        $token = new Token();
        $token->setId(uniqid('request', true));
        return $token;
    }

    /**
     * Persists instance and token data when a token arrives to an activity
     *
     * @param ActivityInterface $activity
     * @param TokenInterface $token
     *
     * @return mixed
     */
    public function persistActivityActivated(ActivityInterface $activity, TokenInterface $token): void
    {
        $this->instanceRepository->persistInstanceUpdated($token->getInstance());
        $user = $token->getInstance()->process->getNextUser($activity, $token);
        $token->status = $token->getStatus();
        $token->element_id = $activity->getId();
        $token->element_type = $activity instanceof ScriptTaskInterface ? 'scriptTask' : 'task';
        $token->element_name = $activity->getName();
        $token->process_id = $token->getInstance()->process->getKey();
        $token->process_request_id = $token->getInstance()->getKey();
        $token->user_id = $user ? $user->getKey() : null;
        //Default 3 days of due date
        $due = $activity->getProperty('dueDate', '72');
        $token->due_at = $due ? Carbon::now()->addHours($due) : null;
        $token->initiated_at = null;
        $token->riskchanges_at = $due ? Carbon::now()->addHours($due * 0.7) : null;
        $token->saveOrFail();
        $token->setId($token->getKey());
    }

    /**
     * Persists instance and token data when a token is completed within an activity
     *
     * @param ActivityInterface $activity
     * @param TokenInterface $token
     *
     * @return mixed
     */
    public function persistActivityCompleted(ActivityInterface $activity, TokenInterface $token): void
    {
        $this->instanceRepository->persistInstanceUpdated($token->getInstance());
        $token->status = $token->getStatus();
        $token->element_id = $activity->getId();
        $token->process_request_id = $token->getInstance()->getKey();
        $token->completed_at = Carbon::now();
        $token->save();
        $token->setId($token->getKey());
    }

    /**
     * Persists instance and token data when a token is closed by an activity
     *
     * @param ActivityInterface $activity
     * @param TokenInterface $token
     *
     * @return mixed
     */
    public function persistActivityClosed(ActivityInterface $activity, TokenInterface $token): void
    {
        $this->instanceRepository->persistInstanceUpdated($token->getInstance());
        $token->status = $token->getStatus();
        $token->element_id = $activity->getId();
        $token->process_request_id = $token->getInstance()->getKey();
        $token->save();
        $token->setId($token->getKey());
    }

    public function persistCatchEventTokenArrives(CatchEventInterface $intermediateCatchEvent, TokenInterface $token): void
    {
        $this->instanceRepository->persistInstanceUpdated($token->getInstance());
        $token->status = $token->getStatus();
        $token->element_id = $intermediateCatchEvent->getId();
        $token->element_type = 'event';
        $token->element_name = $intermediateCatchEvent->getName();
        $token->process_id = $token->getInstance()->process->getKey();
        $token->process_request_id = $token->getInstance()->getKey();
        $token->user_id = null;
        $token->due_at = null;
        $token->initiated_at = null;
        $token->riskchanges_at = null;
        $token->saveOrFail();
        $token->setId($token->getKey());
    }

    public function persistCatchEventTokenConsumed(CatchEventInterface $intermediateCatchEvent, TokenInterface $token): void
    {
        $this->instanceRepository->persistInstanceUpdated($token->getInstance());
        $token->status = 'CLOSED';
        $token->element_id = $intermediateCatchEvent->getId();
        $token->process_request_id = $token->getInstance()->getKey();
        $token->completed_at = Carbon::now();
        $token->save();
        $token->setId($token->getKey());
    }

    public function persistCatchEventMessageArrives(CatchEventInterface $intermediateCatchEvent, TokenInterface $token): void
    {
        $this->instanceRepository->persistInstanceUpdated($token->getInstance());
        $token->status = $token->getStatus();
        $token->element_id = $intermediateCatchEvent->getId();
        $token->element_type = 'event';
        $token->element_name = $intermediateCatchEvent->getName();
        $token->process_id = $token->getInstance()->process->getKey();
        $token->process_request_id = $token->getInstance()->getKey();
        $token->user_id = null;
        $token->due_at = null;
        $token->initiated_at = null;
        $token->riskchanges_at = null;
        $token->saveOrFail();
        $token->setId($token->getKey());
    }

    public function persistCatchEventMessageConsumed(CatchEventInterface $intermediateCatchEvent, TokenInterface $token): void
    {
        $this->instanceRepository->persistInstanceUpdated($token->getInstance());
        $token->status = 'CLOSED';
        $token->element_id = $intermediateCatchEvent->getId();
        $token->process_request_id = $token->getInstance()->getKey();
        $token->completed_at = Carbon::now();
        $token->save();
        $token->setId($token->getKey());
    }
}
