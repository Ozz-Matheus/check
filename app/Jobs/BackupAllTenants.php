<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class BackupAllTenants implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $originalDatabase;

    protected ?string $originalBackupName;

    public function handle(): void
    {

        Tenant::all()->each(function ($tenant) {
            BackupTenantJob::dispatch($tenant);
        });

    }
}
