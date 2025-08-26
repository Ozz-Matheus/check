<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskProbability extends Model
{
    /** @use HasFactory<\Database\Factories\RiskProbabilityFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'score',
    ];

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }
}
