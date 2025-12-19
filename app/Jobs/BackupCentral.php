<?php

namespace App\Jobs;

use App\Traits\LogsToSchedulerFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class BackupCentral implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, LogsToSchedulerFile, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            // 1. Obtener nombre de la DB Central
            $defaultConn = config('database.default');
            $dbName = config("database.connections.{$defaultConn}.database");
            $backupName = 'central-'.$dbName;

            // 2. Configurar nombre dinámico
            config(['backup.backup.name' => $backupName]);

            // 3. Ejecutar Backup
            Artisan::call('backup:run', [
                '--only-db' => false,
                '--disable-notifications' => true,
            ]);

            // 4. Log Exitoso (usando el trait hacia 'backup-central.log')
            $output = Artisan::output();
            $this->logToSchedulerFile(
                "✅ Backup Central [{$dbName}] completado.",
                'backup-central.log'
            );

            // Opcional: Si quieres el output detallado en el log, descomenta:
            // $this->logToSchedulerFile("Detalle: $output", 'backup-central.log');

        } catch (\Exception $e) {
            // 5. Log de Error
            $this->logToSchedulerFile(
                '⚠️ ERROR Backup Central: '.$e->getMessage(),
                'backup-central.log'
            );
        }
    }
}
