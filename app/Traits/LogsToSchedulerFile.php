<?php

namespace App\Traits;

trait LogsToSchedulerFile
{
    protected function logToSchedulerFile(string $message): void
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        file_put_contents(
            storage_path('logs/scheduler.log'),
            "[$timestamp] $message".PHP_EOL,
            FILE_APPEND
        );
    }
}
