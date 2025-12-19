<?php

namespace App\Traits;

trait LogsToSchedulerFile
{
    protected function logToSchedulerFile(string $message): void
    {
        // 1. Forzamos la ruta al storage central (fuera de la carpeta del tenant)
        $filePath = base_path('storage/logs/scheduler.log');

        // Aseguramos que el directorio logs exista (normalmente sí, pero por seguridad)
        $directory = dirname($filePath);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $timestamp = now()->toDateTimeString();

        // 2. Identificamos el tenant en el mensaje para saber de quién es el log
        // Si tenant('id') falla o no hay tenant, mostramos 'Central'
        $tenantId = (function_exists('tenant') && tenant('id'))
            ? tenant('id')
            : 'Central';

        // 3. Escribimos en el archivo único
        file_put_contents(
            $filePath,
            "[{$timestamp}] [Tenant: {$tenantId}] {$message}".PHP_EOL,
            FILE_APPEND
        );
    }
}
