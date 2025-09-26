<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierIssueCause extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierIssueCauseFactory> */
    use HasFactory;

    protected $fillable = ['title'];

    public function supplierIssues()
    {
        return $this->hasMany(SupplierIssue::class);
    }
}
