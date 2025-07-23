<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlType extends Model
{
    /** @use HasFactory<\Database\Factories\ControlTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'process_risk_id',
        'title',
    ];

    public function risk()
    {
        return $this->belongsTo(ProcessRisk::class, 'process_risk_id');
    }

    public function controls()
    {
        return $this->hasMany(Control::class);
    }
}
