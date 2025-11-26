<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class RunCommandForAllTenants implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $commandName,
    ) {}

    public function handle(): void
    {
        Tenant::all()->each(function (Tenant $tenant) {
            $dbName = $tenant->tenancy_db_name ?? $tenant->database ?? $tenant->id;

            if (! $dbName || ! collect(DB::select(
                "SHOW DATABASES LIKE '".addslashes($dbName)."'"
            ))->isNotEmpty()) {
                \Log::warning("🚫 Base de datos no válida para tenant: {$tenant->id}");
                return;
            }

            // Activar tenant (igual que en BackupTenantJob)
            App::forgetInstance('tenant');
            App::instance('tenant', $tenant);
            Config::set('tenancy.tenant', $tenant);

            Config::set('database.connections.mysql.database', $dbName);
            DB::purge('mysql');
            DB::reconnect('mysql');

            // Ejecutar el comando tenant-aware
            Artisan::call($this->commandName);

            App::forgetInstance('tenant');
            Config::offsetUnset('tenancy.tenant');
        });
    }
}
