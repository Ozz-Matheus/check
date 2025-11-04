<?php

namespace App\Models;

use OwenIt\Auditing\Models\Audit as BaseAudit;

class AuditLog extends BaseAudit
{
    protected $table = 'audits'; // importante, apunta a la tabla original
}
