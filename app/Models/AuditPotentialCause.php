<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditPotentialCause extends Model
{
    //
    protected $fillable = [
        'audit_item_id',
        'title',
    ];

    public function auditItem()
    {
        return $this->belongsTo(AuditItem::class, 'audit_item_id');
    }

    public function controls()
    {
        return $this->belongsToMany(AuditControl::class, 'audit_potential_causes_has_audit_controls');
    }
}
