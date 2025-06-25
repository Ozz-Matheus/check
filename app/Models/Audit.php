<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    //
    protected $fillable = [
        'audit_code',
        'start_date',
        'end_date',
        'objective',
        'scope',
        'leader_auditor_id',
        'status_id',
        'audit_criteria_id',
    ];

    public function involvedSubProcesses()
    {
        return $this->belongsToMany(SubProcess::class, 'audit_has_sub_processes');
    }

    public function leaderAuditor()
    {
        return $this->belongsTo(User::class, 'leader_auditor_id');
    }

    public function assignedAuditors()
    {
        return $this->belongsToMany(User::class, 'audit_has_users');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function auditCriteria()
    {
        return $this->belongsTo(AuditCriteria::class, 'audit_criteria_id');
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
