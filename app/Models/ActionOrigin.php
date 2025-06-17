<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionOrigin extends Model
{
    /** @use HasFactory<\Database\Factories\ActionOriginFactory> */
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
        return $this->hasMany(Action::class, 'action_origin_id');
    }
}
