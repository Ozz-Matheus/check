<?php

namespace App\Traits;

use App\Models\Finding;
use App\Models\Status;
use App\Notifications\ActionCreatedNotice;

trait HandlesActionCreation
{
    public ?int $audit_id = null;

    public ?int $control_id = null;

    public ?int $finding_id = null;

    public ?Finding $findingModel = null;

    public function mount(): void
    {
        parent::mount();

        $this->audit_id = request()->route('audit');
        $this->control_id = request()->route('control');
        $this->finding_id = request()->route('finding');

        if ($this->finding_id) {

            $this->findingModel = Finding::findOrFail($this->finding_id);
        }

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $data['finding_id'] = $this->finding_id ?? null;
        if ($this->finding_id) {
            $data['process_id'] = $this->findingModel->control->audit->involved_process_id;
            $data['sub_process_id'] = $this->findingModel->audited_sub_process_id;
        }
        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action', 'proposal')?->id;

        // dd($data);

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
        $breadcrumbs = [];

        if ($this->audit_id && $this->control_id && $this->finding_id) {
            $breadcrumbs[route(
                'filament.dashboard.resources.audits.audit_finding.view',
                [
                    'record' => $this->finding_id,
                    'control' => $this->control_id,
                    'audit' => $this->audit_id,
                ]
            )] = __('Finding');
        }

        $breadcrumbs[false] = __('Create action');

        return $breadcrumbs;
    }

    protected function getRedirectUrl(): string
    {
        if ($this->audit_id && $this->control_id && $this->finding_id) {
            return route(
                'filament.dashboard.resources.audits.audit_finding.view',
                [
                    'record' => $this->finding_id,
                    'control' => $this->control_id,
                    'audit' => $this->audit_id,
                ]
            );
        }

        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }
}
