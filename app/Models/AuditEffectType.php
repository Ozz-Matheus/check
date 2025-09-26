<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditEffectType extends Model
{
    /** @use HasFactory<\Database\Factories\AuditEffectTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function auditControls()
    {
        return $this->hasMany(AuditControl::class);
    }
}
