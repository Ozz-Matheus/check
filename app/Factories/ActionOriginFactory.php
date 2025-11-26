<?php

namespace App\Factories;

use App\Contexts\AuditContext;
use App\Contexts\IncidentAndAccidentContext;
use App\Contexts\RiskContext;
use App\Contracts\ActionOriginInterface;
use App\Models\AuditFinding;
use App\Models\IncidentAndAccident;
use App\Models\Risk;

class ActionOriginFactory
{
    public static function make(string $originType, $model): ?ActionOriginInterface
    {
        return match ($originType) {
            Risk::class => new RiskContext($model),
            AuditFinding::class => new AuditContext($model),
            IncidentAndAccident::class => new IncidentAndAccidentContext($model),
            default => null,
        };
    }
}
