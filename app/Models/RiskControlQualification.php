<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskControlQualification extends Model
{
    /** @use HasFactory<\Database\Factories\RiskControlQualificationFactory> */
    use HasFactory;

    protected $fillable = [
        'context',
        'title',
        'score',
    ];

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    public function controls()
    {
        return $this->hasMany(RiskControl::class);
    }
}
