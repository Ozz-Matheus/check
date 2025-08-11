<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskControlQualification extends Model
{
    /** @use HasFactory<\Database\Factories\RiskControlQualificationFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'score',
    ];

    public function treatments()
    {
        return $this->hasMany(RiskTreatment::class);
    }
}
