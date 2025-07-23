<?php

namespace App\Notifications;

use App\Models\ActionTask;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotice extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('A task has been assigned!'))
            ->view('emails.task-assigned', [
                'user' => $notifiable,
                'task' => $this->task,
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
            ->title($this->task->title)
            ->body(__('A task has been assigned!'))
            ->icon('heroicon-o-archive-box')
            ->color('primary')
            ->status('primary')
            ->getDatabaseMessage();
    }
}
