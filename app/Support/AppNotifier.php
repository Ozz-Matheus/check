<?php

namespace App\Support;

use Filament\Notifications\Notification;

class AppNotifier
{
    protected static function make(string $message, string $type = 'info', ?string $body = null): void
    {
        $notification = Notification::make()
            ->title($message)
            ->duration(5000); // 5 segundos visible por defecto

        if ($body) {
            $notification->body($body);
        }

        match ($type) {
            'success' => $notification->icon('heroicon-o-check-circle')->success(),
            'error', 'danger' => $notification->icon('heroicon-o-x-circle')->danger(),
            'warning' => $notification->icon('heroicon-o-exclamation-triangle')->warning(),
            'info' => $notification->icon('heroicon-o-information-circle')->info(),
            default => $notification,
        };

        $notification->send();
    }

    public static function success(string $message, ?string $body = null): void
    {
        self::make($message, 'success', $body);
    }

    public static function error(string $message, ?string $body = null): void
    {
        self::make($message, 'error', $body);
    }

    public static function warning(string $message, ?string $body = null): void
    {
        self::make($message, 'warning', $body);
    }

    public static function info(string $message, ?string $body = null): void
    {
        self::make($message, 'info', $body);
    }
}
