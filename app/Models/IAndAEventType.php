<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IAndAEventType extends Model
{
    /** @use HasFactory<\Database\Factories\IAndAEventTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'acronym',
    ];

    public function incidentAndAccidents()
    {
        return $this->hasMany(IncidentAndAccident::class);
    }
}
