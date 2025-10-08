<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditControl extends Model
{
    //
    protected $fillable = [
        'audit_item_id',
        'title',
        'nature_of_control_id',
        'control_type_id',
        'control_periodicity_id',
        'tests_to_validate_control',
        'effect_type_id',
        'impact_id',
        'probability_id',
        'level_id',
        'classification_id',
        'qualified',
        'content',
    ];

    protected $casts = [
        'qualified' => 'boolean',
    ];

    public function auditItem()
    {
        return $this->belongsTo(AuditItem::class, 'audit_item_id');
    }

    public function potentialCauses()
    {
        return $this->belongsToMany(AuditPotentialCause::class, 'audit_potential_causes_has_audit_controls');
    }

    public function natureOfControl()
    {
        return $this->belongsTo(AuditNatureOfControl::class, 'nature_of_control_id');
    }

    public function controlType()
    {
        return $this->belongsTo(RiskControlType::class, 'control_type_id');
    }

    public function controlPeriodicity()
    {
        return $this->belongsTo(RiskControlPeriodicity::class, 'control_periodicity_id');
    }

    public function effectType()
    {
        return $this->belongsTo(AuditEffectType::class, 'effect_type_id');
    }

    public function impact()
    {
        return $this->belongsTo(AuditImpact::class, 'impact_id');
    }

    public function probability()
    {
        return $this->belongsTo(AuditProbability::class, 'probability_id');
    }

    public function level()
    {
        return $this->belongsTo(AuditLevel::class, 'level_id');
    }

    public function classification()
    {
        return $this->belongsTo(AuditControlClassification::class, 'classification_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function supportFiles()
    {
        return $this->morphMany(File::class, 'fileable')
            ->whereNull('context')
            ->orWhere('context', 'support');
    }

    public function testDocumentationFiles()
    {
        return $this->morphMany(File::class, 'fileable')
            ->where('context', 'test-documentation');
    }

    public function findings()
    {
        return $this->hasMany(AuditFinding::class);
    }
}
