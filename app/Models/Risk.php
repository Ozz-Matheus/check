<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    //
    protected $fillable = [
        'classification_code',
        'title',
        'process_id',
        'sub_process_id',
        'strategic_context_type_id',
        'strategic_context_id',
        'description',
        'risk_category_id',
        // risk_potential_causes - uno a muchos
        'consequences',
        'inherent_impact_id',
        'inherent_probability_id',
        'inherent_risk_level_id',
        'risk_control_general_qualification_id',
        'residual_risk_level_id',
        // risk_controls - uno a muchos - RelationManager
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }

    public function strategicContextType()
    {
        return $this->belongsTo(RiskStrategicContextType::class, 'strategic_context_type_id');
    }

    public function strategicContext()
    {
        return $this->belongsTo(RiskStrategicContext::class, 'strategic_context_id');
    }

    public function riskCategory()
    {
        return $this->belongsTo(RiskCategory::class, 'risk_category_id');
    }

    public function potentialCauses()
    {
        return $this->hasMany(RiskPotentialCause::class);
    }

    public function inherentImpact()
    {
        return $this->belongsTo(RiskImpact::class, 'inherent_impact_id');
    }

    public function inherentProbability()
    {
        return $this->belongsTo(RiskProbability::class, 'inherent_probability_id');
    }

    public function inherentLevel()
    {
        return $this->belongsTo(RiskLevel::class, 'inherent_risk_level_id');
    }

    public function controlGeneralQualificationCalculated()
    {
        return $this->belongsTo(RiskControlQualification::class, 'risk_control_general_qualification_id');
    }

    public function residualLevelCalculated()
    {
        return $this->belongsTo(RiskLevel::class, 'residual_risk_level_id');
    }

    public function controls()
    {
        return $this->hasMany(RiskControl::class);
    }

    public function actions()
    {
        return $this->morphMany(Action::class, 'origin');
    }
}
