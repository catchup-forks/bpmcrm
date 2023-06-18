<?php
namespace App\Listeners;

use App\Nayra\Contracts\Bpmn\ActivityInterface;
use App\Nayra\Bpmn\Events\ActivityActivatedEvent;
use App\Nayra\Bpmn\Events\ActivityCompletedEvent;
use App\Nayra\Bpmn\Events\ActivityClosedEvent;
use App\Nayra\Contracts\Bpmn\ProcessInterface;
use App\Nayra\Bpmn\Events\ProcessInstanceCreatedEvent;
use Illuminate\Support\Facades\Log;
use App\Nayra\Contracts\Bpmn\ScriptTaskInterface;
use App\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use App\Notifications\ActivityActivatedNotification;
use App\Nayra\Bpmn\Events\ProcessInstanceCompletedEvent;
use App\Notifications\ProcessCompletedNotification;
use App\Nayra\Contracts\Bpmn\TokenInterface;
use App\Facades\WorkflowManager;

/**
 * Description of BpmnSubscriber
 *
 */
final class BpmnSubscriber
{

    /**
     * When a new activity is Activated
     *
     * @param ActivityActivatedEvent $event
     */
    public function ActivityActivated(ActivityActivatedEvent $event): void
    {
        $token = $event->token;
        Log::info('Nofity activity activated: ' . json_encode($token->getProperties(), JSON_THROW_ON_ERROR));

        //Send the notification to the assigned user
        $user = $event->token->user;
        if (!empty($user)) {
            $notification = new ActivityActivatedNotification($event->token);
            $user->notify($notification);
        }
    }

    /**
     * When a process instance is completed.
     *
     * @param ProcessInstanceCreatedEvent $event
     */
    public function ProcessCompleted(ProcessInstanceCompletedEvent $event): void
    {
        //client events
        $user = $event->instance->user;
        $notification = new ProcessCompletedNotification($event->instance);
        $user->notify($notification);

        // Log::info('ProcessCompleted: ' . json_encode($event->instance->getProperties()));
    }

    /**
     * When a process instance is created.
     *
     * @param ProcessInstanceCreatedEvent $event
     */
    public function onProcessCreated(ProcessInstanceCreatedEvent $event): void
    {
        // Log::info('ProcessCreated: ' . json_encode($event->instance->getProperties()));
    }

    /**
     * When an activity is activated.
     *
     * @param ActivityActivatedEvent $event
     */
    public function onActivityActivated(ActivityActivatedEvent $event): void
    {
        // Log::info('ActivityActivated: ' . json_encode($event->token->getProperties()));
        $this->ActivityActivated($event);
    }

    /**
     * When the user completes a task.
     *
     * @param $event
     */
    public function onActivityCompleted(ActivityCompletedEvent $event): void
    {
        // Log::info('ActivityCompleted: ' . json_encode($event->token->getProperties()));
    }

    /**
     * When the activity is closed.
     *
     * @param $event
     */
    public function onActivityClosed(ActivityClosedEvent $event): void
    {
        // Log::info('ActivityClosed: ' . json_encode($event->token->getProperties()));
    }

    /**
     * When a script task is activated.
     *
     * @param ScriptTaskInterface $scriptTask
     * @param TokenInterface $token
     */
    public function onScriptTaskActivated(ScriptTaskInterface $scriptTask, TokenInterface $token): void
    {
        // Log::info('ScriptTaskActivated: ' . $scriptTask->getId());
        WorkflowManager::runScripTask($scriptTask, $token);
    }

    /**
     * When a service task is activated.
     *
     * @param ServiceTaskInterface $serviceTask
     * @param TokenInterface $token
     */
    public function onServiceTaskActivated(ServiceTaskInterface $serviceTask, TokenInterface $token): void
    {
        WorkflowManager::runServiceTask($serviceTask, $token);
    }

    /**
     * Subscription.
     *
     * @param type $events
     */
    public function subscribe($events): void
    {
        $events->listen(ProcessInterface::EVENT_PROCESS_INSTANCE_CREATED, self::class . '@onProcessCreated');
        $events->listen(ProcessInterface::EVENT_PROCESS_INSTANCE_COMPLETED, self::class . '@ProcessCompleted');

        $events->listen(ActivityInterface::EVENT_ACTIVITY_COMPLETED, self::class . '@onActivityCompleted');
        $events->listen(ActivityInterface::EVENT_ACTIVITY_CLOSED, self::class . '@onActivityClosed');

        $events->listen(ActivityInterface::EVENT_ACTIVITY_ACTIVATED, self::class . '@onActivityActivated');
        $events->listen(ScriptTaskInterface::EVENT_SCRIPT_TASK_ACTIVATED, self::class . '@onScriptTaskActivated');
        $events->listen(ServiceTaskInterface::EVENT_SERVICE_TASK_ACTIVATED, self::class . '@onServiceTaskActivated');
    }
}
