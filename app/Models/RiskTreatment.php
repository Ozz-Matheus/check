<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskTreatment extends Model
{
    //
    protected $fillable = [
        'risk_id',
        'responsible_executor_id',
        'risk_control_general_qualification_id',
        'residual_risk_calculated_level_id',
        'residual_impact_id',
        'residual_probability_id',
        'residual_risk_level_id',
    ];

    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    public function controls()
    {
        return $this->hasMany(RiskControl::class);
    }

    public function responsibleExecutor()
    {
        return $this->belongsTo(User::class, 'responsible_executor_id');
    }

    public function controlGeneralQualification()
    {
        return $this->belongsTo(RiskControlQualification::class, 'risk_control_general_qualification_id');
    }

    public function residualRiskCalculatedLevel()
    {
        return $this->belongsTo(RiskLevel::class, 'residual_risk_calculated_level_id');
    }

    public function residualImpact()
    {
        return $this->belongsTo(RiskImpact::class, 'residual_impact_id');
    }

    public function residualProbability()
    {
        return $this->belongsTo(RiskProbability::class, 'residual_probability_id');
    }

    public function residualLevel()
    {
        return $this->belongsTo(RiskLevel::class, 'residual_risk_level_id');
    }

    public function actionImproves()
    {
        return $this->morphMany(Improve::class, 'origin');
    }
}
