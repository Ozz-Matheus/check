<?php

namespace App\Services;

use App\Models\Audit;
use App\Models\Status;

/**
 * Servicio de cambio de estado de para las auditorias
 */
class AuditStatusService
{
    public function statusChangesInAudits(Audit $audit, string $status): bool
    {
        $statusChangeId = Status::byContextAndTitle('audit', $status)?->id;
        $plannedId = Status::byContextAndTitle('audit', 'planned')?->id;

        if (! $statusChangeId) {
            return false;
        }

        // Solo permitir cambio a "in execution" si el estado actual es "planned"
        if ($status === 'in_execution' && $audit->status_id !== $plannedId) {
            return false;
        }

        return $audit->update(['status_id' => $statusChangeId]);
    }
}
