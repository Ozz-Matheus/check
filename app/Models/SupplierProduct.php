<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierProductFactory> */
    use HasFactory;

    protected $fillable = ['supplier_id', 'title', 'product_code'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
