<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlType extends Model
{
    /** @use HasFactory<\Database\Factories\ControlTypeFactory> */
    use HasFactory;

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
        return $this->hasMany(Control::class);
    }
}
