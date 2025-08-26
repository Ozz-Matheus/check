<?php

namespace App\Filament\Resources\RiskControlFollowUpResource\Pages;

use App\Filament\Resources\RiskControlFollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskControlFollowUp extends EditRecord
{
    protected static string $resource = RiskControlFollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
