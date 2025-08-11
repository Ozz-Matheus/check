<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskPlan extends Model
{
    //
    protected $fillable = [
        'process_id',
        'sub_process_id',
        'finished',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }
}
