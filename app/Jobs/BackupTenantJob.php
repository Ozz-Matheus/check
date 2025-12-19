<?php

namespace App\Jobs;

use App\Traits\LogsToSchedulerFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class BackupTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, LogsToSchedulerFile, Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant
    ) {}

    public function handle(): void
    {
        try {
            $this->tenant->run(function () {

                // 1. Configurar nombre del backup
                $identifier = $this->tenant->tenancy_db_name ?? $this->tenant->id;

                // Forzamos la conexión al tenant
                config(['database.connections.mysql.database' => $identifier]);

                // Nombre del archivo zip
                config(['backup.backup.name' => 'tenant-'.$identifier]);

                // 2. Ejecutar Backup
                Artisan::call('backup:run', [
                    '--only-db' => true,
                    '--disable-notifications' => true,
                ]);

                // 3. Log usando el Trait (Especificamos el archivo de backups)
                $output = Artisan::output();
                $this->logToSchedulerFile(
                    "✅ Backup completado para {$identifier}. Output: {$output}",
                    'backup-tenants.log'
                );
            });

        } catch (\Exception $e) {
            // También logueamos el error usando el trait
            $this->logToSchedulerFile(
                "⚠️ ERROR Backup tenant {$this->tenant->id}: ".$e->getMessage(),
                'backup-tenants.log'
            );
        }
    }
}
