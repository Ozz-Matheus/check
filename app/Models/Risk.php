<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    /** @use HasFactory<\Database\Factories\RiskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'process_id',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function audits()
    {
        return $this->belongsToMany(Audit::class, 'audit_has_risks');
    }
}
