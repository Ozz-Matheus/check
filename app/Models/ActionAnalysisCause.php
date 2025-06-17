<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionAnalysisCause extends Model
{
    /** @use HasFactory<\Database\Factories\ActionAnalysisCauseFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function actions()
    {
        return $this->hasMany(Action::class, 'action_analysis_cause_id');
    }
}
