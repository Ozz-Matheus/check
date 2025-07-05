<?php

namespace App\Enums;

enum RiskImpact: int
{
    case Insignificante = 1;
    case Menor = 2;
    case Moderado = 3;
    case Mayor = 4;
    case Catastrofico = 5;

    public function label(): string
    {
        return match ($this) {
            self::Insignificante => 'Insignificante',
            self::Menor => 'Menor',
            self::Moderado => 'Moderado',
            self::Mayor => 'Mayor',
            self::Catastrofico => 'Catastrófico',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
