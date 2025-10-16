<?php

namespace App\Services;

use App\Models\Status;
use Illuminate\Support\Facades\Cache;

class StatusService
{
    public function getAuditStatuses(): array
    {
        return Cache::remember('statuses.audit', 3600, function () {
            return [
                'planned' => Status::byContextAndTitle('internal_audit', 'planned')?->id,
                'in_execution' => Status::byContextAndTitle('internal_audit', 'in_execution')?->id,
                'finished' => Status::byContextAndTitle('internal_audit', 'finished')?->id,
                'canceled' => Status::byContextAndTitle('internal_audit', 'canceled')?->id,
            ];
        });
    }

    public function getIncidentAndAccidentStatuses(): array
    {
        return Cache::remember('statuses.incident_accident', 3600, function () {
            return [
                'reported' => Status::byContextAndTitle('incident_and_accident', 'reported')?->id,
                'in_execution' => Status::byContextAndTitle('incident_and_accident', 'in_execution')?->id,
                'finished' => Status::byContextAndTitle('incident_and_accident', 'finished')?->id,
            ];
        });
    }

    public function getSupplierIssueAndSupplierPortal(): array
    {
        return Cache::remember('statuses.supplier_issue', 3600, function () {
            return [
                'open' => Status::byContextAndTitle('supplier_issue', 'open')?->id,
                'sent' => Status::byContextAndTitle('supplier_issue', 'sent')?->id,
                'read' => Status::byContextAndTitle('supplier_issue', 'read')?->id,
                'answered' => Status::byContextAndTitle('supplier_issue', 'answered')?->id,
                'closed' => Status::byContextAndTitle('supplier_issue', 'closed')?->id,
            ];
        });
    }

    public function getActionAndTaskStatuses(): array
    {
        return Cache::remember('statuses.action_task', 3600, function () {
            return [
                'pending' => Status::byContextAndTitle('action_and_task', 'pending')?->id,
                'in_execution' => Status::byContextAndTitle('action_and_task', 'in_execution')?->id,
                'completed' => Status::byContextAndTitle('action_and_task', 'completed')?->id,
                'overdue' => Status::byContextAndTitle('action_and_task', 'overdue')?->id,
                'extemporaneous' => Status::byContextAndTitle('action_and_task', 'extemporaneous')?->id,
                'canceled' => Status::byContextAndTitle('action_and_task', 'canceled')?->id,
            ];
        });
    }
}
