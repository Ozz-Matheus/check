<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskStrategicContextType extends Model
{
    /** @use HasFactory<\Database\Factories\RiskStrategicContextTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
    ];

    public function strategicContexts()
    {
        return $this->hasMany(RiskStrategicContext::class);
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }
}
