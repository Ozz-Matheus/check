<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocType extends Model
{
    /** @use HasFactory<\Database\Factories\DocTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'acronym',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function docs()
    {
        return $this->hasMany(Doc::class, 'doc_type_id');
    }
}
