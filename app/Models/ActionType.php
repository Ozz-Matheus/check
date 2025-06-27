<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionType extends Model
{
    /** @use HasFactory<\Database\Factories\ActionTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function actions()
    {
        return $this->hasMany(Action::class, 'action_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public static function getIdByKey(string $key): ?int
    {
        return static::where('name', $key)->value('id');
    }
}
