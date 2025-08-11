<?php

namespace App\Filament\Resources\ImproveResource\Pages;

use App\Filament\Resources\ImproveResource;
use App\Traits\HandlesActionCreation;
use Filament\Resources\Pages\CreateRecord;

class CreateImprove extends CreateRecord
{
    protected static string $resource = ImproveResource::class;

    use HandlesActionCreation;
}
