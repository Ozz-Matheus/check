<?php

namespace App\Factories;

use App\Adapters\RiskTreatmentOriginAdapter;
use App\Contracts\ActionOriginInterface;
use App\Models\RiskTreatment;

class ActionOriginFactory
{
    public static function make(string $type, $model): ?ActionOriginInterface
    {
        return match ($type) {
            RiskTreatment::class => new RiskTreatmentOriginAdapter($model),
            default => null,
        };
    }
}
