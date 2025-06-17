<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocExpiration extends Model
{
    /** @use HasFactory<\Database\Factories\DocExpirationFactory> */
    use HasFactory;

    protected $fillable = [
        'doc_type_id',
        'management_review_years',
        'central_expiration_years',
    ];

    protected $casts = [
        'management_review_years' => 'integer',
        'central_expiration_years' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function docType()
    {
        return $this->belongsTo(DocType::class, 'doc_type_id');
    }
}
