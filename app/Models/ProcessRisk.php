<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessRisk extends Model
{
    /** @use HasFactory<\Database\Factories\ProcessRiskFactory> */
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
        return $this->belongsToMany(Audit::class, 'audit_has_process_risks');
    }
}
