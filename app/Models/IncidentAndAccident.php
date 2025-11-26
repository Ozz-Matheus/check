<?php

namespace App\Models;

use App\Traits\BelongsToHeadquarter;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class IncidentAndAccident extends Model implements AuditableContract
{
    use AuditableTrait, BelongsToHeadquarter;

    //
    protected $fillable = [
        'classification_code',
        'title',
        'description',
        'name_affected_person',
        'event_type_id',
        'affected_process_id',
        'affected_sub_process_id',
        /* 'process_id',
        'sub_process_id', */
        'event_date',
        'event_site',
        'responsible_management_process_id',
        'responsible_management_sub_process_id',
        'observations',
        'priority_id',
        'status_id',
        'created_by_id',
        'headquarter_id',
    ];

    public function eventType()
    {
        return $this->belongsTo(IAndAEventType::class, 'event_type_id');
    }

    public function affectedProcess()
    {
        return $this->belongsTo(Process::class, 'affected_process_id');
    }

    public function affectedSubProcess()
    {
        return $this->belongsTo(SubProcess::class, 'affected_sub_process_id');
    }

    public function responsibleManagementProcess()
    {
        return $this->belongsTo(Process::class, 'responsible_management_process_id');
    }

    public function responsibleManagementSubProcess()
    {
        return $this->belongsTo(SubProcess::class, 'responsible_management_sub_process_id');
    }

    /* public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    } */

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function actions()
    {
        return $this->morphMany(Action::class, 'origin');
    }
}
