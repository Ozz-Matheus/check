<?php

namespace App\Models;

class Corrective extends Action
{
    protected $table = 'actions';

    protected static function booted()
    {
        static::addGlobalScope('corrective', function ($query) {
            $query->where('action_type_id', self::CORRECTIVE_TYPE);
        });

        static::creating(function ($model) {
            $model->action_type_id = self::CORRECTIVE_TYPE;
        });
    }

    public const CORRECTIVE_TYPE = 2; // ID correcto de tu seeder

    protected $fillable = [
        'action_type_id',
        'finding_id',
        'title',
        'description',

        'process_id',
        'sub_process_id',
        'action_origin_id',

        'registered_by_id',
        'responsible_by_id',

        'detection_date',

        'containment_action',
        'action_analysis_cause_id',
        'corrective_action',
        'action_verification_method_id',
        'verification_responsible_by_id',
        'verification_date',

        'status_id',
        'deadline',
        'actual_closing_date',
        'reason_for_cancellation',
    ];

    public function analysisCause()
    {
        return $this->belongsTo(ActionAnalysisCause::class, 'action_analysis_cause_id');
    }

    public function verificationMethod()
    {
        return $this->belongsTo(ActionVerificationMethod::class, 'action_verification_method_id');
    }

    public function verificationResponsible()
    {
        return $this->belongsTo(User::class, 'verification_responsible_by_id');
    }
}
