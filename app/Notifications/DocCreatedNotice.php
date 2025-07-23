<?php

namespace App\Notifications;

use App\Models\Doc;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocCreatedNotice extends Notification
{
    use Queueable;

    private $doc;

    /**
     * Create a new notification instance.
     */
    public function __construct(Doc $doc)
    {
        $this->doc = $doc;
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
            ->subject(__('New document created!'))
            ->view('emails.doc-created', [
                'user' => $notifiable,
                'doc' => $this->doc,
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
            ->title($this->doc->title)
            ->body(__('New document created!'))
            ->icon('heroicon-o-archive-box')
            ->color('primary')
            ->status('primary')
            ->getDatabaseMessage();
    }
}
