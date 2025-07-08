<?php

namespace App\Traits;

use App\Models\Audit;

trait HasAuditContext
{
    public ?int $audit_id = null;

    public ?Audit $auditModel = null;

    public function loadAuditContext(): void
    {
        $this->audit_id = request()->route('audit');

        $this->auditModel = Audit::findOrFail($this->audit_id);
    }
}
