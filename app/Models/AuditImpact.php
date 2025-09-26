<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditImpact extends Model
{
    /** @use HasFactory<\Database\Factories\AuditImpactFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'score',
    ];

    public function auditItem()
    {
        return $this->hasMany(AuditItem::class);
    }
}
