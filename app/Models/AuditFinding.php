<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model
{
    //
    protected $fillable = [
        'audit_control_id',
        'title',
        'description',
        'finding_type_id',
        'criteria',
    ];

    public function auditControl()
    {
        return $this->belongsTo(AuditControl::class, 'audit_control_id');
    }

    public function findingType()
    {
        return $this->belongsTo(AuditFindingType::class, 'finding_type_id');
    }

    public function actions()
    {
        return $this->morphMany(Action::class, 'origin');
    }
}
