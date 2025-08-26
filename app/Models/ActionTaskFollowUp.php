<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionTaskFollowUp extends Model
{
    protected $fillable = [
        'action_task_id',
        'content',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function actionTask()
    {
        return $this->belongsTo(ActionTask::class, 'action_task_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
