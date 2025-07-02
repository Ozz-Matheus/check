<?php

namespace App\Traits;

use App\Models\Status;
use App\Notifications\ActionCreatedNotice;

trait HandlesActionCreation
{
    public ?int $audit_id = null;

    public ?int $finding_id = null;

    public function mount(): void
    {
        $this->audit_id = request()->route('audit');
        $this->finding_id = request()->route('finding');

        parent::mount();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['finding_id'] = $this->finding_id ?? null;
        $data['registered_by_id'] = auth()->id();
        $data['status_id'] = Status::byContextAndTitle('action', 'proposal')?->id;

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

        if ($this->audit_id && $this->finding_id) {
            $breadcrumbs[route('filament.dashboard.resources.audits.audit_finding.view', ['record' => $this->finding_id, 'audit' => $this->audit_id])] = __('Finding');
        }

        $breadcrumbs[false] = __('Create action');

        return $breadcrumbs;
    }

    protected function getRedirectUrl(): string
    {
        if ($this->audit_id && $this->finding_id) {
            return route(
                'filament.dashboard.resources.audits.audit_finding.view',
                [
                    'record' => $this->finding_id,
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
