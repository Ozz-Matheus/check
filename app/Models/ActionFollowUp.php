<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionFollowUp extends Model
{
    protected $fillable = [
        'action_id',
        'content',
    ];

    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
