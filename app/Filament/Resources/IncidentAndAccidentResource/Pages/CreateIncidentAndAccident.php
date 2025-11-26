<?php

namespace App\Filament\Resources\IncidentAndAccidentResource\Pages;

use App\Filament\Resources\IncidentAndAccidentResource;
use App\Models\Status;
use App\Services\IncidentAndAccidentService;
use Filament\Resources\Pages\CreateRecord;

class CreateIncidentAndAccident extends CreateRecord
{
    protected static string $resource = IncidentAndAccidentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['classification_code'] = app(IncidentAndAccidentService::class)->generateCode($data['event_type_id'], $data['affected_sub_process_id'], $data['headquarter_id'] ?? null);
        $data['status_id'] = Status::byContextAndTitle('incident_and_accident', 'reported')?->id;
        $data['created_by_id'] = auth()->id();

        return $data;
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
