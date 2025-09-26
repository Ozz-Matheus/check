<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocRecovery extends Model
{
    /** @use HasFactory<\Database\Factories\DocRecoveryFactory> */
    use HasFactory;

    protected $fillable = ['storage_id', 'title'];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function storage()
    {
        return $this->belongsTo(DocStorage::class, 'storage_id');
    }

    public function docs()
    {
        return $this->hasMany(Doc::class);
    }
}
