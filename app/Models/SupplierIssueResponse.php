<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierIssueResponse extends Model
{
    //
    protected $fillable = [
        's_i_id',
        'supplier_response',
        'supplier_actions',
        'response_date',
    ];

    protected $casts = [
        'response_date' => 'date',
    ];

    public function supplierPortal()
    {
        return $this->belongsTo(SupplierPortal::class, 's_i_id');
    }

    public function supplierIssue()
    {
        return $this->belongsTo(SupplierIssue::class, 's_i_id');
    }
}
