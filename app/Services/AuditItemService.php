<?php

namespace App\Services;

use App\Models\AuditItem;

class AuditItemService
{
    protected array $statusIds;

    public function __construct(StatusService $statusService)
    {
        $this->statusIds = $statusService->getAuditStatuses();
    }

    // Cambia el estado de la auditoria interna a en ejecución si el item de auditoria es el primero creado
    public function changeInternalAuditStatusToExecution(AuditItem $auditItem): bool
    {
        if ($auditItem->internalAudit->status_id !== $this->statusIds['planned']) {
            return false;
        }

        return $auditItem->internalAudit->update(['status_id' => $this->statusIds['in_execution']]);
    }
}
