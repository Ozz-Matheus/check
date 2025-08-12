<?php

namespace App\Factories;

use App\Contracts\ActionOriginInterface;
use App\Models\RiskTreatment;
use App\Services\RiskTreatmentService;

class ActionOriginFactory
{
    public static function make(string $type, $model): ?ActionOriginInterface
    {
        return match ($type) {
            RiskTreatment::class => new RiskTreatmentService($model),
            default => null,
        };
    }
}
