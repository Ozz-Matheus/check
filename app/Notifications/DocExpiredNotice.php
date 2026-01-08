<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocExpiredNotice extends Notification implements ShouldQueue
{
    use Queueable;

    public $doc;

    /**
     * Create a new notification instance.
     */
    public function __construct($doc)
    {
        $this->doc = $doc;
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
            ->subject(__('⚠️ Document Expired!'))
            ->view('emails.doc-expired', [
                'user' => $notifiable,
                'doc' => $this->doc,
            ]);

    }
}
