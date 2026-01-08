<?php

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Filament\Admin\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenants extends ListRecords
{
    protected static string $resource = TenantResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),

            Actions\Action::make('import_db')
                ->label(__('Link Existing DB'))
                ->icon('heroicon-o-server')
                ->color('warning')
                ->url(fn () => TenantResource::getUrl('import')),
        ];
    }
}
