<?php

namespace App\Models;

use App\Filament\Resources\ActionResource\Forms\CorrectiveSchema;
use App\Filament\Resources\ActionResource\Forms\ImproveSchema;
use App\Filament\Resources\ActionResource\Forms\PreventiveSchema;
use App\Traits\HasFilamentResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /** @use HasFactory<\Database\Factories\ActionFactory> */
    use HasFactory, HasFilamentResource;

    protected $guarded = [];

    /* protected $fillable = [
        'action_type_id',
        'finding_id',
        'title',
        'description',

        'process_id',
        'sub_process_id',
        'action_origin_id',

        'registered_by_id',
        'responsible_by_id',

        // Correctiva / Preventiva
        // 'detection_date',

        // Solo para Correctiva
        'containment_action',
        'action_analysis_cause_id',
        'corrective_action',
        'action_verification_method_id',
        'verification_responsible_by_id',
        'verification_date',

        // Solo para Preventiva
        'risk_probability',
        'risk_impact',
        'risk_evaluation',
        'prevention_action',
        'effectiveness_indicator',

        // Mejora / Preventiva
        'expected_impact',

        'status_id',
        'deadline',
        'actual_closing_date',
        'reason_for_cancellation',
    ]; */

    protected $casts = [
        'deadline' => 'date',
        'actual_closing_date' => 'date',
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
        return $this->belongsTo(ActionType::class, 'action_type_id');
    }

    public function finding()
    {
        return $this->belongsTo(Finding::class, 'finding_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function subProcess()
    {
        return $this->belongsTo(SubProcess::class);
    }

    public function origin()
    {
        return $this->belongsTo(ActionOrigin::class, 'action_origin_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_id');
    }

    public function responsibleBy()
    {
        return $this->belongsTo(User::class, 'responsible_by_id');
    }

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

    public function getUrlAttribute(): ?string
    {
        return $this->getFilamentUrl();
    }

    public static function getDynamicSchema($typeAction): array
    {
        return match ($typeAction) {
            'corrective' => CorrectiveSchema::get(),
            'preventive' => PreventiveSchema::get(),
            'improve' => ImproveSchema::get(),
            default => [],
        };
    }
}
