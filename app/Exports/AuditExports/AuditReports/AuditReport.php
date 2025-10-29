<?php

namespace App\Exports\AuditExports\AuditReports;

use App\Models\InternalAudit;

class AuditReport
{
    public static function make($auditId)
    {
        $audit = InternalAudit::with([
            'process',
            'subProcess.leader',
            'priority',
            'status',
            'internalAuditQualification',
            'evaluatedBy',
            'createdBy',
            'auditItems' => function ($query) {
                $query->with([
                    'activity',
                    'controls' => function ($query) {
                        $query->with([
                            'natureOfControl', 'controlType', 'controlPeriodicity',
                            'effectType', 'impact', 'probability', 'level', 'classification',
                            'findings' => function ($query) {
                                $query->with([
                                    'findingType',
                                    'actions' => fn ($q) => $q->with(['responsibleBy', 'status']),
                                ]);
                            },
                        ]);
                    },
                ]);
            },
        ])->findOrFail($auditId);

        return [
            'audit' => $audit,
        ];
    }
}
