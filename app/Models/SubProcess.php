<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProcess extends Model
{
    /** @use HasFactory<\Database\Factories\SubProcessFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'process_id',
        'acronym',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function leaders()
    {
        return $this->belongsToMany(User::class, 'users_lead_subprocesses');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_sub_processes');
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
