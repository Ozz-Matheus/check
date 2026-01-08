<?php

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Filament\Admin\Resources\TenantResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;
use TomatoPHP\FilamentTenancy\Models\Tenant;

use function Filament\Support\is_app_url;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    /**
     * @throws Throwable
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation(collect($data)->except('domain')->toArray());
        $record->domains()->create(['domain' => collect($data)->get('domain')]);

        return $record;
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        $this->callHook('beforeValidate');

        $data = $this->form->getState();

        $this->callHook('afterValidate');

        $data = $this->mutateFormDataBeforeCreate($data);

        $this->callHook('beforeCreate');

        $this->record = $this->handleRecordCreation($data);

        $this->form->model($this->getRecord())->saveRelationships();

        $this->callHook('afterCreate');

        $this->rememberData();

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $redirectUrl = $this->getRedirectUrl();
        $record = $this->record;

        // 1. Aseguramos el user_id en la tabla tenants
        DB::table('tenants')
            ->where('id', $this->record->id)
            ->update([
                'user_id' => $data['user_id'],
            ]);

        // 2. Conexión a la Base de Datos del Tenant
        try {
            if (! config('filament-tenancy.single_database')) {
                $dbName = config('tenancy.database.prefix').$record->id.config('tenancy.database.suffix');
                config(['database.connections.dynamic.database' => $dbName]);
            }
            DB::purge('dynamic');
            DB::connection('dynamic')->getPdo();
        } catch (\Exception $e) {
            throw new \Exception("Failed to connect to tenant database: {$dbName}");
        }

        // 3. Preparar datos del Usuario
        $userData = [
            'name' => $record->name,
            'email' => $record->email,
            'password' => $record->password,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Definir criterios de búsqueda
        $searchAttributes = ['email' => $record->email];

        if (config('filament-tenancy.single_database')) {
            $searchAttributes['tenant_id'] = $record->id;
            $userData['tenant_id'] = $record->id;
        }

        // 4. Crear o Actualizar usando ELOQUENT (para obtener la instancia)
        $tenantUser = User::on('dynamic')->updateOrCreate(
            $searchAttributes,
            $userData
        );

        // 5. Asignar el Rol
        $tenantUser->assignRole('super_admin');

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }

    /**
     * @throws Throwable
     */
    private function createTenantRecord(array $data)
    {
        \Log::info('Saving Tenant');
        $record = new Tenant(collect($data)->except('domain')->toArray());
        $record->saveOrFail();
        \Log::info('Saving Domains');
        $record = $record::find($record->id);
        $record->domains()->create(['domain' => collect($data)->get('domain')]);

        return $record;
    }
}
