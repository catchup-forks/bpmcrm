<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Process;
use App\Models\ProcessRequestToken as Token;
use App\Nayra\Contracts\Bpmn\TokenInterface;

final class ActivityActivatedNotification extends Notification
{
    use Queueable;

    private $processUid;
    private $tokenUid;
    private $tokenElement;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TokenInterface $token)
    {
        $this->processUid = $token->processRequest->process->getKey();
        $this->tokenUid = $token->getKey();
        $this->tokenElement = $token->element_id;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(mixed $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(mixed $notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array
     */
    public function toDatabase(mixed $notifiable)
    {
        return $this->toArray($notifiable);
    }

    /*
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $process = Process::find($this->processUid);
        $definitions = $process->getDefinitions();
        $activity = $definitions->getActivity($this->tokenElement);
        $token = Token::find($this->tokenUid);
        $request = $token->processRequest;
        return [
            'message' => sprintf('Task created: %s', $activity->getName()),
            'name' => $activity->getName(),
            'processName' => $process->name,
            'request_id' => $request->getKey(),
            'userName' => $token->user->getFullName(),
            'dateTime' => $token->created_at->toIso8601String(),
            'uid' => $this->tokenUid,
            'url' => sprintf(
                '/tasks/%s/edit',
                $this->tokenUid
            )
        ];
    }

    /*
     * Get the broadcast representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

}
