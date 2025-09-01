<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionSource extends Model
{
    /** @use HasFactory<\Database\Factories\ActionSourceFactory> */
    use HasFactory;

    protected $fillable = ['title'];

    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}
