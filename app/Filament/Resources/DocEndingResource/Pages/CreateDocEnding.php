<?php

namespace App\Filament\Resources\DocEndingResource\Pages;

use App\Filament\Resources\DocEndingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocEnding extends CreateRecord
{
    protected static string $resource = DocEndingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
