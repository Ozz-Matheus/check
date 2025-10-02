<?php

namespace App\Traits;

use Filament\Notifications\Notification;

trait AppNotifier
{
    /**
     * Método genérico para enviar notificaciones.
     */
    protected function notify(string $message, string $type = 'success'): void
    {
        $notification = Notification::make()->title($message);

        match ($type) {
            'success' => $notification->success(),
            'error', 'danger' => $notification->danger(),
            'info' => $notification->info(),
            'warning' => $notification->warning(),
            default => $notification,
        };

        $notification->send();
    }

    // Helpers que usan el genérico:
    protected function notifySuccess(string $message): void
    {
        $this->notify($message, 'success');
    }

    protected function notifyError(string $message): void
    {
        $this->notify($message, 'error');
    }

    protected function notifyInfo(string $message): void
    {
        $this->notify($message, 'info');
    }
}
