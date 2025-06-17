<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocEnding extends Model
{
    /** @use HasFactory<\Database\Factories\DocEndingFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'label',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function docs()
    {
        return $this->hasMany(Doc::class, 'doc_ending_id');
    }
}
