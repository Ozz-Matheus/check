<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskPotentialCause extends Model
{
    //
    protected $fillable = [
        'risk_id',
        'title',
    ];

    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    public function controls()
    {
        return $this->belongsToMany(RiskControl::class, 'risk_potential_causes_has_risk_controls');
    }
}
