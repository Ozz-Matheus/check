<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Factories\ActionOriginFactory;
use App\Filament\Resources\ActionResource;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;
use App\Services\IncidentAndAccidentService;
use Filament\Resources\Pages\CreateRecord;

class CreateAction extends CreateRecord
{
    protected static string $resource = ActionResource::class;

    public ?string $originType = null;

    public ?int $originId = null;

    public ?string $originLabel = null;

    public ?int $processId = null;

    public ?int $subProcessId = null;

    public function mount(): void
    {
        parent::mount();

        if (request()->route('model') || request()->route('model_id')) {

            $modelRequest = request()->route('model');

            $modelName = "App\\Models\\{$modelRequest}";

            // Validamos que la clase exista
            if (! class_exists($modelName)) {

                abort(404);
            }

            $register = $modelName::findOrFail(request()->route('model_id'));

            $resgisteId = $register->id;

            $this->originType = $modelName;

            $this->originId = $resgisteId;

            $factory = ActionOriginFactory::make($this->originType, $register);

            $this->originLabel = $factory?->getLabel();
            $this->processId = $factory?->getProcessId();
            $this->subProcessId = $factory?->getSubProcessId();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($this->originType && $this->originId) {
            $data['origin_type'] = $this->originType;
            $data['origin_id'] = $this->originId;
            $data['origin_label'] = $this->originLabel;
            $data['process_id'] = $this->processId;
            $data['sub_process_id'] = $this->subProcessId;
        } else {
            $data['origin_label'] = __('Independent');
        }

        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action_and_task', 'pending')?->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->responsible_by_id && $this->record->responsibleBy) {
            $this->record->responsibleBy->notify(new ActionCreatedNotice($this->record));
        }
        if ($this->originType === "App\Models\IncidentAndAccident") {
            $incidentAndAccident = $this->record->origin;
            app(IncidentAndAccidentService::class)->changeIncidentAndAccidentStatusToExecution($incidentAndAccident);
        }
    }

    public function getBreadcrumbs(): array
    {
        if ($this->originType && $this->originId) {
            $model = $this->originType::findOrFail($this->originId);
            $breadCrumbs = ActionOriginFactory::make($this->originType, $model)->getBreadcrumbs();

            return $breadCrumbs + [false => __('Create')];
        }

        return parent::getBreadcrumbs();
    }

    protected function getRedirectUrl(): string
    {
        if ($this->record->origin_type && $this->record->origin_id) {
            $redirect = ActionOriginFactory::make($this->record->origin_type, $this->record->origin)->getRedirectUrl();

            return $redirect;
        }

        return parent::getResource()::getUrl('view', ['record' => $this->record]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
