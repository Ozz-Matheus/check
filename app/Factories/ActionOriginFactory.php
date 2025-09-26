<?php

namespace App\Factories;

use App\Contexts\AuditContext;
use App\Contexts\IncidentAndAccidentContext;
use App\Contexts\RiskContext;
use App\Contexts\SupplierContext;
use App\Contracts\ActionOriginInterface;
use App\Models\AuditFinding;
use App\Models\IncidentAndAccident;
use App\Models\Risk;
use App\Models\SupplierIssue;

class ActionOriginFactory
{
    public static function make(string $originType, $model): ?ActionOriginInterface
    {
        return match ($originType) {
            Risk::class => new RiskContext($model),
            AuditFinding::class => new AuditContext($model),
            // SupplierIssue::class => new SupplierContext($model),
            IncidentAndAccident::class => new IncidentAndAccidentContext($model),
            default => null,
        };
    }
}
