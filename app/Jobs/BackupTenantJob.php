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

class BackupTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle(): void
    {
        $tenant = $this->tenant;
        $dbName = $tenant->tenancy_db_name ?? $tenant->database ?? $tenant->id;

        if (! $dbName || ! collect(DB::select("SHOW DATABASES LIKE '".addslashes($dbName)."'"))->isNotEmpty()) {
            \Log::warning("🚫 Base de datos no válida para tenant: {$tenant->id}");

            return;
        }

        App::forgetInstance('tenant');
        App::instance('tenant', $tenant);
        Config::set('tenancy.tenant', $tenant);

        Config::set('database.connections.mysql.database', $dbName);
        DB::purge('mysql');
        DB::reconnect('mysql');

        Config::set('backup.backup.name', 'tenant-'.$dbName);

        // Artisan::call('backup:clean', ['--disable-notifications' => true]);
        Artisan::call('backup:run', ['--only-db' => true, '--disable-notifications' => true]);

        file_put_contents(
            storage_path('logs/backup-tenants.log'),
            '['.now()->toDateTimeString()."] [{$dbName}] ".Artisan::output()."\n",
            FILE_APPEND
        );

        App::forgetInstance('tenant');
        Config::offsetUnset('tenancy.tenant');
    }
}
