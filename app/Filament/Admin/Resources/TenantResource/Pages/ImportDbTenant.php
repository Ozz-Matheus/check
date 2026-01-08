<?php

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Filament\Admin\Resources\TenantResource;
use App\Services\ExistingTenantCreatorService;
use App\Support\AppNotifier;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class ImportDbTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Verificar duplicado antes de continuar
        if (Tenant::where('id', $data['id'])->exists()) {

            AppNotifier::error(
                'Error',
                "La base de datos [{$data['id']}] ya está asignada a otro tenant.",
                true
            );

            // Lanzamos una excepción para detener la creación
            Log::info('This database is already assigned to another tenant.');

            abort(403);
        }

        // Asignamos el tenant al usuario actual
        $data['user_id'] = auth()->id();

        // Limpiar confirmación
        unset($data['password_confirmation']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Tenant
    {
        return ExistingTenantCreatorService::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
