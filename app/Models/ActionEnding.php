<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionEnding extends Model
{
    protected $fillable = [
        'action_id',
        'real_impact',
        'result',
        'extemporaneous_reason',
        'real_closing_date',
        'estimated_evaluation_date',
        'effectiveness',
        'evaluation_comment',
        'real_evaluation_date',
    ];

    protected $casts = [
        'real_closing_date' => 'date',
        'estimated_evaluation_date' => 'date',
        'real_evaluation_date' => 'date',
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
