<?php

namespace App\Notifications;

use App\Models\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActionDeadlineNotice extends Notification
{
    use Queueable;

    private $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(Action $action)
    {
        $this->action = $action;
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
            ->subject(__('Action close to expiration!'))
            ->view('emails.action-expiration', [
                'user' => $notifiable,
                'action' => $this->action,
            ]);

    }
}
