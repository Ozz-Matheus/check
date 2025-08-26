<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionEnding extends Model
{
    protected $fillable = [
        'action_id',
        'real_impact',
        'result',
        'effectiveness',
        'evaluation_comment',
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

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
