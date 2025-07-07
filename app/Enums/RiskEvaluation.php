<?php

namespace App\Enums;

enum RiskEvaluation: int
{
    case Bajo = 1;
    case Medio = 2;
    case Alto = 3;
    case Critico = 4;

    public static function fromScore(int $score): self
    {
        return match (true) {
            $score <= 5 => self::Bajo,
            $score <= 10 => self::Medio,
            $score <= 15 => self::Alto,
            default => self::Critico,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Bajo => 'Bajo',
            self::Medio => 'Medio',
            self::Alto => 'Alto',
            self::Critico => 'Crítico',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
