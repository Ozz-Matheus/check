<?php

namespace App\Filament\Resources\CorrectiveResource\Pages;

use App\Filament\Resources\CorrectiveResource;
use App\Traits\HandlesActionCreation;
use Filament\Resources\Pages\CreateRecord;

class CreateCorrective extends CreateRecord
{
    protected static string $resource = CorrectiveResource::class;

    use HandlesActionCreation;
}
