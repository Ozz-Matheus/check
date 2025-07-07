<?php

namespace App\Models;

use App\Traits\HasStatusMetadata;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\StatusFactory> */
    use HasFactory, HasStatusMetadata;

    protected $fillable = [
        'context',
        'title',
        'label',
        'color',
        'icon',
        'protected',
    ];

    protected $casts = [
        'protected' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function docVersions()
    {
        return $this->hasMany(DocVersion::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function actionTasks()
    {
        return $this->hasMany(ActionTask::class);
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    public function controls()
    {
        return $this->hasMany(Control::class);
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
