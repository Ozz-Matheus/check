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
        'involved_process_id',
        'leader_auditor_id',
        'status_id',
        'audit_criteria_id',
    ];

    public function involvedProcess()
    {
        return $this->belongsTo(Process::class, 'involved_process_id');
    }

    public function processRisks()
    {
        return $this->belongsToMany(ProcessRisk::class, 'audit_has_process_risks');
    }

    public function controls()
    {
        return $this->hasMany(Control::class);
    }

    public function leaderAuditor()
    {
        return $this->belongsTo(User::class, 'leader_auditor_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function auditCriteria()
    {
        return $this->belongsTo(AuditCriteria::class, 'audit_criteria_id');
    }
}
