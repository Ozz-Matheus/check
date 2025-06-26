<?php

namespace App\Models;

class Improve extends Action
{
    protected $table = 'actions';

    protected static function booted()
    {
        static::addGlobalScope('improve', function ($query) {
            $query->where('action_type_id', self::IMPROVEMENT_TYPE);
        });

        static::creating(function ($model) {
            $model->action_type_id = self::IMPROVEMENT_TYPE;
        });
    }

    public const IMPROVEMENT_TYPE = 1; // ID correcto de tu seeder

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

        'expected_impact',

        'status_id',
        'deadline',
        'actual_closing_date',
        'reason_for_cancellation',
    ];
}
