<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditFindingType extends Model
{
    /** @use HasFactory<\Database\Factories\AuditFindingTypeFactory> */
    use HasFactory;

    protected $fillable = ['title'];

    public function auditFindings()
    {
        return $this->hasMany(AuditFinding::class);
    }
}
