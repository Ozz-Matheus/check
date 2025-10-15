<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskLevel extends Model
{
    /** @use HasFactory<\Database\Factories\RiskLevelFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'min',
        'max',
        'color',
    ];
}
