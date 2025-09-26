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

    // documentos
    public function docs()
    {
        return $this->hasMany(Doc::class);
    }

    // acciones
    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    // riesgos
    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    // auditorias internas
    public function internalAudits()
    {
        return $this->hasMany(InternalAudit::class);
    }

    public function auditSubProcessActivities()
    {
        return $this->hasMany(AuditSubProcessActivity::class);
    }
}
