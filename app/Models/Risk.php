<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    //
    protected $fillable = [
        'risk_plan_id',
        'strategic_context_type_id',
        'strategic_context_id',
        'risk_description',
        'risk_category_id',
        // risk_potential_causes - uno a muchos
        'consequences',
        'inherent_impact_id',
        'inherent_probability_id',
        'inherent_risk_level_id',
        // risk_treatment - uno a uno
    ];

    public function riskPlan()
    {
        return $this->belongsTo(RiskPlan::class, 'risk_plan_id');
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

    public function treatment()
    {
        return $this->hasOne(RiskTreatment::class, 'risk_id');
    }
}
