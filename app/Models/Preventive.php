<?php

namespace App\Models;

class Preventive extends Action
{
    protected $table = 'actions';

    protected static function booted()
    {
        static::addGlobalScope('preventive', function ($query) {
            $query->where('action_type_id', self::PREVENTIVE_TYPE);
        });

        static::creating(function ($model) {
            $model->action_type_id = self::PREVENTIVE_TYPE;
        });
    }

    public const PREVENTIVE_TYPE = 3; // ID correcto de tu seeder

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

        'risk_probability',
        'risk_impact',
        'risk_evaluation',
        'prevention_action',
        'effectiveness_indicator',

        'expected_impact',

        'status_id',
        'deadline',
        'actual_closing_date',
        'reason_for_cancellation',
    ];

    public static function evaluateRiskLevel(?int $probability, ?int $impact): string
    {
        $risk = ($probability ?? 0) * ($impact ?? 0);

        return match (true) {
            $risk <= 5 => 'Bajo',
            $risk <= 10 => 'Medio',
            $risk <= 15 => 'Alto',
            default => 'Crítico',
        };
    }
}
