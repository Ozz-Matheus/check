<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OverdueService
{
    public static function markAsOverdue(string $modelClass, string $dateColumn = 'limit_date', string $statusColumn = 'status_id'): int
    {
        // 1. Instanciar el servicio explícitamente para asegurar datos frescos del tenant actual
        $statusService = app(StatusService::class);

        // 2. Obtenemos los IDs
        $statusIds = $statusService->getActionAndTaskStatuses();

        // 3. Validación de seguridad: Aseguramos que existan las claves necesarias
        if (! isset($statusIds['pending'], $statusIds['in_execution'], $statusIds['overdue'])) {
            Log::warning('⚠️ OverdueService: Faltan estados configurados en el tenant actual.', [
                'tenant' => tenant('id'),
                'found_statuses' => array_keys($statusIds),
            ]);

            return 0;
        }

        $today = Carbon::today()->toDateString();

        // 4. Ejecutar actualización masiva
        return $modelClass::query()
            // OPTIMIZACIÓN: Usamos 'where' simple en lugar de 'whereDate' para aprovechar índices SQL
            // Al comparar un campo DATE/DATETIME con un string 'YYYY-MM-DD', MySQL lo maneja bien.
            ->where($dateColumn, '<=', $today)
            ->whereIn($statusColumn, [
                $statusIds['pending'],
                $statusIds['in_execution'],
            ])
            // Solo actualizamos si no están ya vencidos (doble check de seguridad)
            ->where($statusColumn, '!=', $statusIds['overdue'])
            ->update([$statusColumn => $statusIds['overdue']]);
    }
}
