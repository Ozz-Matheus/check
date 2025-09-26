<?php

namespace App\Observers;

use App\Models\AuditItem;
use App\Services\InternalAuditService;

class AuditItemObserver
{
    public function __construct(protected InternalAuditService $internalAuditService) {}

    /**
     * Handle the AuditItem "created" event.
     */
    /* public function created(AuditItem $auditItem): void
    {
        //
    } */

    /**
     * Handle the AuditItem "updated" event.
     */
    public function updated(AuditItem $auditItem): void
    {
        //
        $this->internalAuditService->recalculateInternalAuditQualifications($auditItem->internalAudit);
    }

    /**
     * Handle the AuditItem "deleted" event.
     */
    /* public function deleted(AuditItem $auditItem): void
    {
        //
    } */

    /**
     * Handle the AuditItem "restored" event.
     */
    /* public function restored(AuditItem $auditItem): void
    {
        //
    } */

    /**
     * Handle the AuditItem "force deleted" event.
     */
    /* public function forceDeleted(AuditItem $auditItem): void
    {
        //
    } */
}
