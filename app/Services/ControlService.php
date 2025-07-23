<?php

namespace App\Services;

use App\Models\Control;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para las tareas
 */
class ControlService
{
    public function createFiles(Control $control, array $data): void
    {
        foreach ($data['path'] ?? [] as $path) {

            $fileName = $data['name'][$path] ?? basename($path);

            $fileMetadata = [
                'name' => $fileName,
                'path' => $path,
                'mime_type' => Storage::disk('public')->mimeType($path),
                'size' => Storage::disk('public')->size($path),
            ];

            $control->files()->create($fileMetadata);
        }

        $this->taskNotification(__('Support files uploaded successfully'));
    }

    // Métodos auxiliares privados.

    private function taskNotification(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }
}
