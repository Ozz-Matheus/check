<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskControlPeriodicity extends Model
{
    /** @use HasFactory<\Database\Factories\RiskControlPeriodicityFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function control()
    {
        return $this->hasMany(RiskControl::class);
    }
}
