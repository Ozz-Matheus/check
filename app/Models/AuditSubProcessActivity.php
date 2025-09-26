<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditSubProcessActivity extends Model
{
    /** @use HasFactory<\Database\Factories\AuditSubProcessActivityFactory> */
    use HasFactory;

    protected $fillable = [
        'process_id',
        'sub_process_id',
        'title',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }
}
