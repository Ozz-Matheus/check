<?php

namespace App\Traits;

use App\Factories\ActionOriginFactory;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;

trait HandlesActionCreation
{
    public ?string $originType = null;

    public ?int $originId = null;

    public ?string $originLabel = null;

    public ?int $processId = null;

    public ?int $subProcessId = null;

    protected $modelService = null;

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

            $this->modelService = ActionOriginFactory::make($this->originType, $register);

            $this->originLabel = $this->modelService->getLabel();
            $this->processId = $this->modelService->getProcessId();
            $this->subProcessId = $this->modelService->getSubProcessId();

        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($this->originType) {

            $data['origin_type'] = $this->originType;
            $data['origin_id'] = $this->originId;
            $data['origin_label'] = $this->originLabel;
            $data['process_id'] = $this->processId;
            $data['sub_process_id'] = $this->subProcessId;

        } else {

            $data['origin_label'] = __('Independent');

        }

        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action', 'proposal')?->id;

        // dd($data, $this->processId, $this->subProcessId);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->responsible_by_id && $this->record->responsibleBy) {
            $this->record->responsibleBy->notify(new ActionCreatedNotice($this->record));
        }
    }

    public function getBreadcrumbs(): array
    {
        return [
            ...($this->modelService?->getBreadcrumbs() ?? []),
            false => __('Create action'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->modelService?->getRedirectUrl()
            ?? $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
