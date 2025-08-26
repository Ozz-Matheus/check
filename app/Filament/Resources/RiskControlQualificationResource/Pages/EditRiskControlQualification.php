<?php

namespace App\Filament\Resources\RiskControlQualificationResource\Pages;

use App\Filament\Resources\RiskControlQualificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskControlQualification extends EditRecord
{
    protected static string $resource = RiskControlQualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
