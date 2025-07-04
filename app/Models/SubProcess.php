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
        'leader_by_id',
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

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_by_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_sub_processes');
    }

    public function docs()
    {
        return $this->hasMany(Doc::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    /* public function audits()
    {
        return $this->belongsToMany(Audit::class, 'audit_has_sub_processes');
    } */ // se retira este metodo por la eliminación de la tabla pivot (audit_has_sub_processes)

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
