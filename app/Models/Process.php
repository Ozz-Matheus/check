<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    /** @use HasFactory<\Database\Factories\ProcessFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function subProcesses()
    {
        return $this->hasMany(SubProcess::class);
    }

    public function docs()
    {
        return $this->hasMany(Doc::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }
}
