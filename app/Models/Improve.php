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
}
