<?php

namespace App\Traits;

trait FixedMorphType
{
    public static function bootFixedMorphType(): void
    {
        static::addGlobalScope('fixed_morph', function ($query) {
            $query->where('fileable_type', static::$fixedMorphType);
        });

        static::creating(function ($model) {
            $model->fileable_type = static::$fixedMorphType;
        });
    }
}
