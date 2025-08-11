<?php

namespace App\Filament\Resources\PreventiveResource\Pages;

use App\Filament\Resources\PreventiveResource;
use App\Traits\HandlesActionCreation;
use Filament\Resources\Pages\CreateRecord;

class CreatePreventive extends CreateRecord
{
    protected static string $resource = PreventiveResource::class;

    use HandlesActionCreation;
}
