<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalAuditQualification extends Model
{
    /** @use HasFactory<\Database\Factories\InternalAuditQualificationFactory> */
    use HasFactory;

    protected $fillable = ['title', 'min', 'max'];

    public function internalAudit()
    {
        return $this->hasMany(InternalAudit::class);
    }
}
