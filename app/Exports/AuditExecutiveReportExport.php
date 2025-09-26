<?php

namespace App\Exports;

use App\Models\InternalAudit;

class AuditExecutiveReportExport
{
    public static function make($auditId)
    {
        $audit = InternalAudit::with([
            'process',
            'subProcess',
            'priority',
            'status',
            'internalAuditQualification',
            'evaluatedBy',
            'createdBy',
        ])->findOrFail($auditId);

        return [
            'audit' => $audit,
        ];
    }
}
