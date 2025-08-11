<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskStrategicContext extends Model
{
    /** @use HasFactory<\Database\Factories\RiskStrategicContextFactory> */
    use HasFactory;

    protected $fillable = [
        'strategic_context_type_id',
        'title',
    ];

    public function strategicContextType()
    {
        return $this->belongsTo(RiskStrategicContextType::class, 'strategic_context_type_id');
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }
}
