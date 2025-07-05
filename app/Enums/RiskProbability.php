<?php

namespace App\Enums;

enum RiskProbability: int
{
    case MuyBaja = 1;
    case Baja = 2;
    case Media = 3;
    case Alta = 4;
    case MuyAlta = 5;

    public function label(): string
    {
        return match ($this) {
            self::MuyBaja => 'Muy baja',
            self::Baja => 'Baja',
            self::Media => 'Media',
            self::Alta => 'Alta',
            self::MuyAlta => 'Muy alta',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
