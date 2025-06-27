<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    //
    protected $fillable = [
        'audit_id',
        'title',
        'audited_sub_process_id',
        'type_of_finding',
        'description',
        'criteria_not_met',
        'responsible_auditor_id',
        'status_id',
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'audit_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'audited_sub_process_id');
    }

    public function responsibleAuditor()
    {
        return $this->belongsTo(User::class, 'responsible_auditor_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function getMappedActionType(): string
    {
        return match ($this->type_of_finding) {
            'major_nonconformity' => 'corrective',
            'observation' => 'preventive',
            'opportunity_for_improvement' => 'improve',
            default => 'improve',
        };
    }
}
