<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLevel extends Model
{
    /** @use HasFactory<\Database\Factories\AuditLevelFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'min',
        'max',
        'score',
        'color',
    ];

    public function auditItem()
    {
        return $this->hasMany(AuditItem::class);
    }
}
