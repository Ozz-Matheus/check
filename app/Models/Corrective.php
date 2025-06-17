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

    // protected $fillable = [
    //     // 'support_file_id',
    //     // 'verifier_user_id',
    //     // 'analysis_cause_id',
    //     // 'cause_description',
    // ];

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    // public function verifier()
    // {
    //     return $this->belongsTo(User::class, 'verifier_user_id');
    // }

    // public function analysisCause()
    // {
    //     return $this->belongsTo(Status::class, 'analysis_cause_id');
    // }

}
