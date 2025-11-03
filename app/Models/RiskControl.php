<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskControl extends Model
{
    //
    protected $fillable = [
        'risk_id',
        'context_type',
        'title',
        'control_periodicity_id',
        'control_type_id',
        'control_qualification_id',
    ];

    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    public function potentialCauses()
    {
        return $this->belongsToMany(RiskPotentialCause::class, 'risk_potential_causes_has_risk_controls');
    }

    public function periodicity()
    {
        return $this->belongsTo(RiskControlPeriodicity::class, 'control_periodicity_id');
    }

    public function controlType()
    {
        return $this->belongsTo(RiskControlType::class, 'control_type_id');
    }

    public function controlQualification()
    {
        return $this->belongsTo(RiskControlQualification::class, 'control_qualification_id');
    }

    public function followUps()
    {
        return $this->hasMany(RiskControlFollowUp::class);
    }
}
