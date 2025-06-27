<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Models\ActionType;
use App\Models\Finding;
use App\Models\Status;
use Filament\Resources\Pages\CreateRecord;

class CreateAction extends CreateRecord
{
    protected static string $resource = ActionResource::class;

    public ?int $finding_id = null;

    public $typeAction = null;

    public function mount(): void
    {
        $this->finding_id = request()->route('finding');

        $finding = Finding::findOrFail($this->finding_id);

        $this->typeAction = $finding->getMappedActionType();

        parent::mount();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action', 'proposal')?->id;
        $data['action_type_id'] = ActionType::getIdByKey($this->typeAction);
        $data['finding_id'] = $this->finding_id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return match ($this->typeAction) {
            'corrective' => \App\Filament\Resources\CorrectiveResource::getUrl('index'),
            'preventive' => \App\Filament\Resources\PreventiveResource::getUrl('index'),
            'improve' => \App\Filament\Resources\ImproveResource::getUrl('index'),
            default => '/',
        };
    }

    protected function getCreatedNotificationRedirectUrl(): ?string
    {
        return $this->getRedirectUrl();
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
