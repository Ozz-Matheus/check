<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    /** @use HasFactory<\Database\Factories\ControlFactory> */
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'control_type_id',
        'title',
        'comment',
        'status_id',
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class, 'audit_id');
    }

    public function controlType()
    {
        return $this->belongsTo(ControlType::class, 'control_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function controlFiles()
    {
        return $this->hasMany(ControlFile::class, 'fileable_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
