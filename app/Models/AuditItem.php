<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditItem extends Model
{
    //
    protected $fillable = [
        'internal_audit_id',
        'activity_id',
        'risk_description',
        'risk_category_id',
        'consequences',
        'general_level_id',
    ];

    public function internalAudit()
    {
        return $this->belongsTo(InternalAudit::class, 'internal_audit_id');
    }

    public function activity()
    {
        return $this->belongsTo(AuditSubProcessActivity::class, 'activity_id');
    }

    public function riskCategory()
    {
        return $this->belongsTo(RiskCategory::class, 'risk_category_id');
    }

    public function potentialCauses()
    {
        return $this->hasMany(AuditPotentialCause::class);
    }

    public function generalLevel()
    {
        return $this->belongsTo(AuditLevel::class, 'general_level_id');
    }

    public function controls()
    {
        return $this->hasMany(AuditControl::class);
    }
}
