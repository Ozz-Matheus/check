<?php

namespace App\Models;

use App\Traits\BelongsToHeadquarter;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class SupplierIssue extends Model implements AuditableContract
{
    use AuditableTrait, BelongsToHeadquarter;

    //
    protected $fillable = [
        'title',
        'cause_id',
        'description',
        'entry_date',
        'report_date',
        'supplier_id',
        'product_id',
        'amount',
        'supplier_lot',
        'monetary_impact',
        'responsible_by_id',
        'status_id',
        'headquarter_id',
    ];

    public function cause()
    {
        return $this->belongsTo(SupplierIssueCause::class, 'cause_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function product()
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }

    public function responsibleBy()
    {
        return $this->belongsTo(User::class, 'responsible_by_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function responses()
    {
        return $this->hasOne(SupplierIssueResponse::class, 'supplier_issue_id');
    }
}
