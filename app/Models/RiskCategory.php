<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskCategory extends Model
{
    /** @use HasFactory<\Database\Factories\RiskCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }
}
