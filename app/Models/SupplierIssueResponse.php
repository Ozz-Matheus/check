<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierIssueResponse extends Model
{
    //
    protected $fillable = [
        'supplier_issue_id',
        'supplier_response',
        'supplier_actions',
        'response_date',
        'effectiveness',
        'evaluation_comment',
        'evaluation_date',
    ];

    protected $casts = [
        'response_date' => 'date',
    ];

    public function supplierPortal()
    {
        return $this->belongsTo(SupplierPortal::class, 'supplier_issue_id');
    }

    public function supplierIssue()
    {
        return $this->belongsTo(SupplierIssue::class, 'supplier_issue_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
