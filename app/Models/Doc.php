<?php

namespace App\Models;

use App\Services\DocService;
use App\Support\AppNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Doc extends Model implements AuditableContract
{
    use AuditableTrait;

    //
    protected $fillable = [
        'classification_code',
        'title',
        'process_id',
        'sub_process_id',
        'doc_type_id',
        'central_expiration_date',
        'storage_method_id',
        'recovery_method_id',
        'disposition_method_id',
        'display_restriction',
        'created_by_id',
    ];

    protected $casts = [
        'central_expiration_date' => 'date',
        'display_restriction' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function storageMethod()
    {
        return $this->belongsTo(DocStorage::class, 'storage_method_id');
    }

    public function recoveryMethod()
    {
        return $this->belongsTo(DocRecovery::class, 'recovery_method_id');
    }

    public function dispositionMethod()
    {
        return $this->belongsTo(DocDisposition::class, 'disposition_method_id');
    }

    public function accessToAdditionalUsers()
    {
        return $this->belongsToMany(User::class, 'docs_has_confidential_users', 'doc_id', 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id')->withDefault();
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
        return $this->central_expiration_date && today()->isAfter($this->central_expiration_date);
    }

    // Determina si el documento está por vencer en los próximos 30 días.
    public function getIsAboutToExpireAttribute(): bool
    {
        if (! $this->central_expiration_date || $this->is_expired) {
            return false;
        }

        $today = today();
        $expiration = $this->central_expiration_date;

        // El documento no está vencido y caducará en 30 días o menos.
        return $today->isBefore($expiration) && $today->diffInDays($expiration) <= 30;
    }

    public function reactivateDoc(): void
    {
        $service = app(DocService::class);
        $docTypeExpiration = $service->getDocTypeExpiration($this->doc_type_id);
        $centralExpirationDate = today()->addYears($docTypeExpiration);

        $this->update([
            'central_expiration_date' => $centralExpirationDate,
        ]);

        AppNotifier::success(__('Document active'));
    }
}
