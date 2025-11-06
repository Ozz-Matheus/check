<?php

namespace App\Filament\Admin\Resources\ExistingTenantResource\Pages;

use App\Filament\Admin\Resources\ExistingTenantResource;
use App\Services\ExistingTenantCreatorService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class CreateExistingTenant extends CreateRecord
{
    protected static string $resource = ExistingTenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Verificar duplicado antes de continuar
        if (Tenant::where('id', $data['id'])->exists()) {

            Notification::make()
                ->danger()
                ->title('Error')
                ->body("La base de datos [{$data['id']}] ya está asignada a otro tenant.")
                ->send();

            // Lanzamos una excepción para detener la creación
            Log::info('This database is already assigned to another tenant.');

            abort(403);
        }

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
        return route('filament.admin.resources.tenants.index'); // Redirige al recurso oficial
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
