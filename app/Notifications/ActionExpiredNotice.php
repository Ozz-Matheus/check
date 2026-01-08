<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActionExpiredNotice extends Notification implements ShouldQueue
{
    use Queueable;

    public $action;

    /**
     * Create a new notification instance.
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('⚠️ Action Expired!'))
            ->view('emails.action-expired', [
                'user' => $notifiable,
                'action' => $this->action,
            ]);
    }
}
