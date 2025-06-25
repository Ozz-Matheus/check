<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditCriteria extends Model
{
    /** @use HasFactory<\Database\Factories\AuditCriteriaFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }
}
