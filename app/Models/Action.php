<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /** @use HasFactory<\Database\Factories\ActionFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'action_type_id',
        'source_id',
        'process_id',
        'sub_process_id',
        'registered_by_id',
        'responsible_by_id',
        'detection_date',
        'action_analysis_cause_id',
        'root_cause',
        'containment_actions',
        'action_verification_method_id',
        'verification_responsible_by_id',
        'expected_impact',
        'limit_date',
        'status_id',
        'finished',
        'cancellation_date',
        'origin_type',
        'origin_id',
        'origin_label',
    ];

    protected $casts = [
        'detection_date' => 'date',
        'limit_date' => 'date',
        'finished' => 'boolean',
        'cancellation_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Carga ansiosa por defecto para evitar N+1
    protected $with = ['origin', 'source'];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function origin()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->belongsTo(ActionType::class, 'action_type_id');
    }

    public function source()
    {
        return $this->belongsTo(ActionSource::class, 'source_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class);
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_id');
    }

    public function responsibleBy()
    {
        return $this->belongsTo(User::class, 'responsible_by_id');
    }

    /* Correctiva */
    public function analysisCause()
    {
        return $this->belongsTo(ActionAnalysisCause::class, 'action_analysis_cause_id');
    }

    public function verificationMethod()
    {
        return $this->belongsTo(ActionVerificationMethod::class, 'action_verification_method_id');
    }

    public function verificationResponsible()
    {
        return $this->belongsTo(User::class, 'verification_responsible_by_id');
    }
    /* ** */

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function followUps()
    {
        return $this->hasMany(ActionFollowUp::class, 'action_id');
    }

    public function tasks()
    {
        return $this->hasMany(ActionTask::class, 'action_id');
    }

    public function ending()
    {
        return $this->hasOne(ActionEnding::class, 'action_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accesores / Métodos útiles
    |--------------------------------------------------------------------------
    */

    public function getSubtitleAttribute(): string
    {
        $label = $this->origin_label;
        $title = $this->origin?->title ?? $this->source?->title ?? '—';

        return "{$label} : {$title}";
    }
}
