<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocDisposition extends Model
{
    /** @use HasFactory<\Database\Factories\DocDispositionFactory> */
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
