<?php

namespace App\Models;

use App\Services\DocService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Doc extends Model
{
    /** @use HasFactory<\Database\Factories\DocFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'process_id',
        'sub_process_id',
        'doc_type_id',
        'classification_code',
        'created_by_id',
        'management_review_date',
        'central_expiration_date',
        'doc_ending_id',
        'expiration',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'management_review_date' => 'date',
        'central_expiration_date' => 'date',
        'expiration' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function type()
    {
        return $this->belongsTo(DocType::class, 'doc_type_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id')->withDefault();
    }

    public function ending()
    {
        return $this->belongsTo(DocEnding::class, 'doc_ending_id');
    }

    public function versions()
    {
        return $this->hasMany(DocVersion::class);
    }

    public function latestVersion()
    {
        return $this->hasOne(DocVersion::class)->latestOfMany('version');
    }

    public function latestApprovedVersion()
    {
        return $this->hasOne(DocVersion::class)
            ->where('status_id', 3)
            ->latest('version');
    }

    /*
    |--------------------------------------------------------------------------
    | Doc Services
    |--------------------------------------------------------------------------
    */

    public function docService(): DocService
    {
        return app(DocService::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function approvedVersionUrl(): ?string
    {
        return $this->latestApprovedVersion?->file->path
            ? Storage::url($this->latestApprovedVersion->file->path)
            : null;
    }

    public function hasApprovedVersion(): bool
    {
        return ! empty($this->latestApprovedVersion?->file->path);
    }

    public function getContextPath(): string
    {
        $processTitle = $this->process?->title ?? null;
        $subprocessTitle = $this->subProcess?->title ?? null;

        return "{$processTitle} / {$subprocessTitle}";
    }

    // Determina si el documento está vencido.
    public function getIsExpiredAttribute(): bool
    {
        return $this->central_expiration_date && now()->greaterThan($this->central_expiration_date);
    }

    // Determina si el documento está por vencer en los próximos 30 días.
    public function getIsAboutToExpireAttribute(): bool
    {
        if (! $this->central_expiration_date) {
            return false;
        }

        $now = now();
        $expiration = $this->central_expiration_date;

        return $now->lessThanOrEqualTo($expiration) && $now->diffInDays($expiration) <= 30;
    }
}
