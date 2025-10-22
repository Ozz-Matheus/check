<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class TenantStorageInitializer
{
    public function ensureStorageStructure(string $tenantId): void
    {
        $tenantId = preg_replace('/[^a-zA-Z0-9_\-]/', '', $tenantId);

        $suffixBase = config('tenancy.filesystem.suffix_base');
        $tenantStoragePath = storage_path();
        $publicPath = public_path("{$suffixBase}{$tenantId}");

        // Asegura carpetas necesarias
        File::ensureDirectoryExists("{$tenantStoragePath}/app/public", 0777, true);
        File::ensureDirectoryExists("{$tenantStoragePath}/framework/cache", 0777, true);

        // Si el symlink no existe, lo crea
        if (! File::exists($publicPath)) {

            try {
                resolve(Filesystem::class)->link(
                    "{$tenantStoragePath}/app/public",
                    $publicPath
                );
            } catch (\Throwable $e) {
                // Si por alguna razón falla, lo ignora o loguea:
                \Log::warning("No se pudo crear enlace simbólico para tenant {$tenantId}: {$e->getMessage()}");
            }

        }
    }
}
