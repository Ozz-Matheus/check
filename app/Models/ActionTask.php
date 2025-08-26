<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionTask extends Model
{
    protected $fillable = [
        'action_id',
        'title',
        'detail',
        'responsible_by_id',
        'start_date',
        'deadline',
        'actual_start_date',
        'actual_closing_date',
        'status_id',
        'finished',
        'extemporaneous_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'actual_start_date' => 'date',
        'actual_closing_date' => 'date',
        'finished' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function responsibleBy()
    {
        return $this->belongsTo(User::class, 'responsible_by_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function followUps()
    {
        return $this->hasMany(ActionTaskFollowUp::class, 'action_task_id');
    }
}
