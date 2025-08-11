<?php

namespace App\Traits;

use App\Contracts\ActionOriginInterface;
use App\Factories\ActionOriginFactory;
use App\Models\RiskTreatment;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;

trait HandlesActionCreation
{
    public ?string $originType = null;

    public ?int $originId = null;

    public ?string $originLabel = null;

    public ?int $processId = null;

    public ?int $subProcessId = null;

    protected ?ActionOriginInterface $originAdapter = null;

    public function mount(): void
    {
        parent::mount();

        if ($riskTreatmentId = request()->route('treatment')) {
            $model = RiskTreatment::findOrFail($riskTreatmentId);
            $this->originType = RiskTreatment::class;
            $this->originId = $riskTreatmentId;
            $this->originAdapter = ActionOriginFactory::make(RiskTreatment::class, $model);
            // Esto de aca abajo me toco asignarlos aca de esta manera para
            $this->originLabel = $this->originAdapter->getLabel();
            $this->processId = $this->originAdapter->getProcessId();
            $this->subProcessId = $this->originAdapter->getSubProcessId();
        }

        /* if ($auditId = request()->route('audit')) {
            $this->originType = Audit::class;
            $this->originId = $auditId;
        } */
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($this->originAdapter) {
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
            ...($this->originAdapter?->getBreadcrumbs() ?? []),
            false => __('Create action'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->originAdapter?->getRedirectUrl() ?? $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
