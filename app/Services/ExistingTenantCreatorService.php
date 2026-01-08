<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;
use TomatoPHP\FilamentTenancy\Models\Tenant;

class ExistingTenantCreatorService
{
    public static function create(array $data): Tenant
    {

        // 1️⃣ Crear tenant sin eventos automáticos
        $tenant = new Tenant;
        $tenant->id = $data['id'];
        $tenant->name = $data['name'];
        $tenant->email = $data['email'];
        $tenant->phone = $data['phone'];
        $tenant->password = $data['password'];
        $tenant->is_active = $data['is_active'] ?? true;
        $tenant->saveQuietly();

        // Update Data

        DB::table('tenants')
            ->where('id', $tenant->id)
            ->update([
                'user_id' => $data['user_id'],
                'data' => json_encode([
                    'created_at' => $tenant->created_at?->toDateTimeString(),
                    'updated_at' => $tenant->updated_at?->toDateTimeString(),
                    'tenancy_db_name' => $tenant->id,
                ]),
            ]);

        // End Update Data

        // 2️⃣ Asociar dominio
        Domain::create([
            'domain' => $data['domain'],
            'tenant_id' => $tenant->id,
        ]);

        // 3️⃣ Configurar conexión dinámica
        Config::set('database.connections.dynamic.database', $tenant->id);
        Config::set('database.default', 'dynamic');
        DB::purge('dynamic');
        DB::connection('dynamic')->getPdo();

        // 4️⃣ Ejecutar migraciones directamente desde PHP
        /** @var \Illuminate\Database\Migrations\Migrator $migrator */
        $migrator = app('migrator');
        $migrationPath = database_path('migrations/tenant');

        if (! $migrator->repositoryExists()) {
            $migrator->getRepository()->createRepository();
        }

        $migrator->usingConnection('dynamic', function () use ($migrator, $migrationPath) {
            $migrator->run([$migrationPath]);
        });

        // 5️⃣ Ejecutar seeder directamente desde PHP
        if (class_exists(\Database\Seeders\DatabaseSeeder::class)) {
            // Forzar que el seeder use la conexión 'dynamic'
            Config::set('database.default', 'dynamic');

            /** @var \Illuminate\Database\Seeder $seeder */
            $seeder = app(\Database\Seeders\DatabaseSeeder::class);
            $seeder->__invoke();

            // Restaurar la conexión por defecto
            Config::set('database.default', 'mysql');
        }

        // 6️⃣ Crear usuario admin inicial
        try {

            // A. Cambiamos la conexión por defecto a 'dynamic' (la del tenant)
            Config::set('database.default', 'dynamic');

            // B. Usamos firstOrCreate para obtener la INSTANCIA del modelo
            $user = User::firstOrCreate(
                ['email' => $tenant->email], // Busca por email
                [
                    'name' => $tenant->name,
                    'password' => $data['password'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // C. Ahora sí tenemos el objeto $user y podemos asignar el rol
            $user->assignRole('super_admin');

        } catch (\Throwable $e) {
            report($e);
        }

        // 7️⃣ Restaurar conexión predeterminada
        Config::set('database.default', 'mysql');

        return $tenant;
    }
}
