<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    //
    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'name',
        'path',
        'mime_type',
        'size',
        'context',
    ];

    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function fileable()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function scopeContext($query, $context)
    {
        return $query->where('context', $context);
    }

    public function url(): ?string
    {
        return Storage::url($this->path);
    }

    /**
     * URL absoluta garantizada (para Office Viewer).
     */
    public function absoluteUrl(): string
    {
        $url = $this->url();

        // Si $url ya es absoluta (S3), URL::to() la deja igual.
        return URL::to($url);
    }

    public function getReadableMimeTypeAttribute(): string
    {
        return match ($this->mime_type) {
            'application/pdf' => 'PDF',
            'application/msword' => 'Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word',
            'application/vnd.ms-excel' => 'Excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel',
            default => __('Otro'),
        };
    }

    public function getReadableSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes < 1024) {
            return $bytes.' B';
        }

        $pow = floor(log($bytes, 1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / (1024 ** $pow), 2).' '.$units[$pow];
    }

    // Helpers para la Blade:
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf' || str_ends_with(strtolower($this->name), '.pdf');
    }

    public function isOfficeEmbeddable(): bool
    {
        $officeMimes = [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        if (in_array($this->mime_type, $officeMimes, true)) {
            return true;
        }

        return (bool) preg_match('/\.(docx?|xlsx?)$/i', $this->name);
    }
}
