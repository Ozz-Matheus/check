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
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'actual_start_date' => 'date',
        'actual_closing_date' => 'date',
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

    public function taskComments()
    {
        return $this->hasMany(Comment::class, 'commentable_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function taskFiles()
    {
        return $this->hasMany(ActionTaskFile::class, 'fileable_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
