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
}
