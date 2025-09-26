<?php

namespace App\Filament\Resources\SupplierIssueCauseResource\Pages;

use App\Filament\Resources\SupplierIssueCauseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupplierIssueCause extends EditRecord
{
    protected static string $resource = SupplierIssueCauseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
