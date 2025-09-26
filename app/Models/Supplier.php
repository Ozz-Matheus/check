<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    protected $fillable = ['title', 'supplier_code'];

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class);
    }
}
