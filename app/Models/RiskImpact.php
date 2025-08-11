<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskImpact extends Model
{
    /** @use HasFactory<\Database\Factories\RiskImpactFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'score',
    ];

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    /* public function treatments()
    {
        return $this->hasMany(RiskTreatment::class);
    } */ // 📌Se implementará en la segunda fase
}
