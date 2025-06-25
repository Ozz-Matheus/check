<?php

namespace App\Traits;

use App\Models\Audit;

trait HasAuditContext
{
    public ?int $audit_id = null;

    public ?Audit $AuditModel = null;

    public function loadAuditContext(): void
    {
        $this->audit_id = request()->route('audit');

        $this->AuditModel = Audit::findOrFail($this->audit_id);
    }
}
