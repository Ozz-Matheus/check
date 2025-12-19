<?php

namespace App\Notifications;

use App\Models\Doc;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocDeadlineNotice extends Notification
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Document close to expiration'))
            ->view('emails.doc-expiration', [
                'user' => $notifiable,
                'doc' => $this->doc,
            ]);

    }
}
