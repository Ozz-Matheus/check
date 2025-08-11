<?php

namespace App\Filament\Resources\RiskLevelResource\Pages;

use App\Filament\Resources\RiskLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskLevel extends EditRecord
{
    protected static string $resource = RiskLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
