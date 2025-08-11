<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskControlType extends Model
{
    /** @use HasFactory<\Database\Factories\RiskControlTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function control()
    {
        return $this->hasMany(RiskControl::class);
    }
}
