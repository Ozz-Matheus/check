<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionVerificationMethod extends Model
{
    /** @use HasFactory<\Database\Factories\ActionVerificationMethodFactory> */
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
        return $this->hasMany(Action::class, 'action_verification_method_id');
    }
}
