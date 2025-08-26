<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskControlFollowUp extends Model
{
    //
    protected $fillable = [
        'risk_control_id',
        'content',
        'control_qualification_id',
    ];

    public function control()
    {
        return $this->belongsTo(RiskControl::class, 'risk_control_id');
    }

    public function controlQualification()
    {
        return $this->belongsTo(RiskControlQualification::class, 'control_qualification_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
