<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class RunCommandForAllTenants implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $commandName,
    ) {}

    public function handle(): void
    {
        Tenant::all()->each(function ($tenant) {
            try {
                $tenant->run(function () {
                    Artisan::call($this->commandName);
                });
            } catch (\Exception $e) {
                Log::warning("⚠️ Error en tenant {$tenant->id}: ".$e->getMessage());
            }
        });
    }
}
