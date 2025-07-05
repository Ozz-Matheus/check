<?php

namespace App\Enums;

enum RiskEvaluation: string
{
    case Bajo = 'Bajo';
    case Medio = 'Medio';
    case Alto = 'Alto';
    case Critico = 'Crítico';

    public static function fromScore(int $score): self
    {
        return match (true) {
            $score <= 5 => self::Bajo,
            $score <= 10 => self::Medio,
            $score <= 15 => self::Alto,
            default => self::Critico,
        };
    }
}
