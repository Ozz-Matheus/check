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
        'limit_date',
        'real_start_date',
        'real_closing_date',
        'status_id',
        'finished',
        'extemporaneous_reason',
        'reason_for_cancellation',
        'cancellation_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'limit_date' => 'date',
        'real_start_date' => 'date',
        'real_closing_date' => 'date',
        'cancellation_date' => 'date',
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
