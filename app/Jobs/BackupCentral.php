<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class BackupCentral
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {

        $defaultConn = config('database.default');
        $dbName = config("database.connections.{$defaultConn}.database");

        // Establecer nombre único del backup
        Config::set('backup.backup.name', 'central-'.$dbName);

        // Limpiar respaldos antiguos
        // Artisan::call('backup:clean', [
        //     '--disable-notifications' => true,
        // ]);

        // Ejecutar backup de solo la base de datos
        Artisan::call('backup:run', [
            '--only-db' => false,
            '--disable-notifications' => true,
        ]);

        // Log dedicado
        file_put_contents(
            storage_path('logs/backup-central.log'),
            '['.now()->toDateTimeString()."] [{$dbName}] ".Artisan::output()."\n",
            FILE_APPEND
        );
    }
}
