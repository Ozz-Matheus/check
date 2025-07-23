<?php

namespace App\Notifications;

use App\Models\DocVersion;
use App\Models\Status;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VersionStatusNotice extends Notification
{
    use Queueable;

    private $version;

    private $status;

    private $changeReason;

    /**
     * Create a new notification instance.
     */
    public function __construct(DocVersion $version, Status $status, $changeReason = null)
    {
        //
        $this->version = $version;
        $this->status = $status;
        $this->changeReason = $changeReason;
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
            ->subject(__('New version status!'))
            ->view('emails.version-status', [
                'user' => $notifiable,
                'version' => $this->version,
                'status' => $this->status,
                'changeReason' => $this->changeReason,
            ]);
    }

    /**
     * Guardar la notificación en la base de datos.
     */
    public function toDatabase(User $notifiable)
    {
        return FilamentNotification::make()
            ->title($this->version->file?->name)
            ->body(__('Document status: ').ucfirst(strtolower($this->status->label)))
            ->icon($this->status->iconName())
            ->color($this->status->colorName())
            ->status($this->status->colorName())
            ->getDatabaseMessage();
    }
}
