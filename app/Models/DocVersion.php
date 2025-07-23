<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocVersion extends Model
{
    /** @use HasFactory<\Database\Factories\DocVersionFactory> */
    use HasFactory;

    protected $fillable = [
        'version',
        'comment',
        'change_reason',
        'sha256_hash',
        'decided_at',
        'status_id',
        'doc_id',
        'created_by_id',
        'decided_by_id',
    ];

    protected $casts = [
        'version' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'decided_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id')->withDefault();
    }

    public function decidedBy()
    {
        return $this->belongsTo(User::class, 'decided_by_id')->withDefault();
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function isLatestVersion(): bool
    {
        return $this->id === self::where('doc_id', $this->doc_id)
            ->orderByDesc('version')
            ->first()?->id;
    }

    public function isCompliant(): bool
    {
        return $this->isLatestVersion()
            && ! empty($this->sha256_hash)
            && optional($this->status)->title === 'approved'
            && optional($this->doc)->classification_code
            && optional($this->doc)->doc_ending_id;
    }
}
