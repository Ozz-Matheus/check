<?php

namespace App\Services;

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
            symlink("{$tenantStoragePath}/app/public", $publicPath);
        }
    }
}
