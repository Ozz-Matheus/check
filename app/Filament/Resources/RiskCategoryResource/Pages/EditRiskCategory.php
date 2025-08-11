<?php

namespace App\Filament\Resources\RiskCategoryResource\Pages;

use App\Filament\Resources\RiskCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiskCategory extends EditRecord
{
    protected static string $resource = RiskCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
