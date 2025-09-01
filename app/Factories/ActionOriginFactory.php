<?php

namespace App\Factories;

use App\Contexts\RiskContext;
use App\Contracts\ActionOriginInterface;
use App\Models\Risk;

class ActionOriginFactory
{
    public static function make(string $originType, $model): ?ActionOriginInterface
    {
        return match ($originType) {
            Risk::class => new RiskContext($model),
            default => null,
        };
    }
}
