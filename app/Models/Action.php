<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /** @use HasFactory<\Database\Factories\ActionFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'detection_date' => 'date',
        'limit_date' => 'date',
        'finished' => 'boolean',
        'cancellation_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

    public function ActionSubtitle(): string
    {
        // Usar el operador ternario para definir el valor de $origin
        $origin = ($this->origin_type !== null && $this->origin_id !== null)
            ? $this->origin_label.' : '.$this->origin_type::find($this->origin_id)?->title
            : $this->origin_label.' : '.$this->source->title;

        return $origin;
    }
}
