<?php

namespace App\Filament\Resources\PreventiveResource\Pages;

use App\Filament\Resources\PreventiveResource;
use App\Traits\HandlesActionCreation;
use Filament\Resources\Pages\CreateRecord;

class CreatePreventive extends CreateRecord
{
    use HandlesActionCreation;

    protected static string $resource = PreventiveResource::class;
}
