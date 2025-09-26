<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentAndAccident extends Model
{
    //
    protected $fillable = [
        'classification_code',
        'title',
        'description',
        'name_affected_person',
        'event_type_id',
        'process_id',
        'sub_process_id',
        'event_date',
        'event_site',
        'priority_id',
        'status_id',
        'created_by_id',
    ];

    public function eventType()
    {
        return $this->belongsTo(IAndAEventType::class, 'event_type_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class, 'sub_process_id');
    }

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
