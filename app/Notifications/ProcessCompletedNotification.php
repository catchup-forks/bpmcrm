<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ProcessRequest as Instance;
use App\Nayra\Contracts\Engine\ExecutionInstanceInterface;

final class ProcessCompletedNotification extends Notification
{
    use Queueable;
    private $processName;
    private $instanceUid;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ExecutionInstanceInterface $instance)
    {
        $this->processName = $instance->process->name;
        $this->instanceUid = $instance->getKey();
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

    public function toDatabase(mixed $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(mixed $notifiable): array
    {
        $instance = Instance::find($this->instanceUid);
        return [
            'name' => sprintf('Completed: %s', $this->processName),
            'dateTime' => $instance->completed_at->toIso8601String(),
            'uid' => $this->processName,
            'request_id' => $instance->getKey(),
            'url' => '/process',
        ];
    }

    public function toBroadcast(mixed $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

}
