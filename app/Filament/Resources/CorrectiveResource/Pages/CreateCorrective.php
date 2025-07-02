<?php

namespace App\Filament\Resources\CorrectiveResource\Pages;

use App\Filament\Resources\CorrectiveResource;
use App\Traits\HandlesActionCreation;
use Filament\Resources\Pages\CreateRecord;

class CreateCorrective extends CreateRecord
{
    use HandlesActionCreation;

    protected static string $resource = CorrectiveResource::class;
}
