<?php

namespace App\Notifications;

use App\Models\Action;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActionCreatedNotice extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New action created!'))
            ->view('emails.action-created', [
                'user' => $notifiable,
                'action' => $this->action,
            ]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(User $notifiable)
    {
        return FilamentNotification::make()
            ->title($this->action->title)
            ->body(__('New action created !'))
            ->icon('heroicon-o-archive-box')
            ->color('primary')
            ->status('primary')
            ->getDatabaseMessage();
    }
}
