<?php

namespace App\Notifications;

use App\Models\ActionTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeadlineNotice extends Notification
{
    use Queueable;

    private $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(ActionTask $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Task close to expiration!'))
            ->view('emails.task-expiration', [
                'user' => $notifiable,
                'task' => $this->task,
            ]);

    }
}
