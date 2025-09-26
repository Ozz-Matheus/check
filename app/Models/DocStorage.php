<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocStorage extends Model
{
    /** @use HasFactory<\Database\Factories\DocStorageFactory> */
    use HasFactory;

    protected $fillable = ['name', 'label'];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function docs()
    {
        return $this->hasMany(Doc::class);
    }

    public function disposition()
    {
        return $this->hasMany(DocDisposition::class);
    }

    public function recovery()
    {
        return $this->hasMany(DocRecovery::class);
    }
}
