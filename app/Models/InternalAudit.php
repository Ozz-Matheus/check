<?php

namespace App\Models;

use App\Traits\BelongsToHeadquarter;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class InternalAudit extends Model implements AuditableContract
{
    use AuditableTrait, BelongsToHeadquarter;

    //
    protected $fillable = [
        'classification_code',
        'title',
        'process_id',
        'sub_process_id',
        'objective',
        'scope',
        'audit_date',
        'priority_id',
        'status_id',
        'internal_audit_qualification_id',
        'qualification_value',
        'observations',
        'created_by_id',
        'evaluated_by_id',
        'headquarter_id',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function internalAuditQualification()
    {
        return $this->belongsTo(InternalAuditQualification::class, 'internal_audit_qualification_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by_id');
    }

    public function auditItems()
    {
        return $this->hasMany(AuditItem::class);
    }
}
