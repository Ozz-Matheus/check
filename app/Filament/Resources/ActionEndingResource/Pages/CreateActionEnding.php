<?php

namespace App\Filament\Resources\ActionEndingResource\Pages;

use App\Filament\Resources\ActionEndingResource;
use App\Filament\Resources\ActionResource;
use App\Models\Action;
use App\Models\ActionEnding;
use App\Services\ActionEndingService;
use App\Services\FileService;
use Filament\Resources\Pages\CreateRecord;

class CreateActionEnding extends CreateRecord
{
    protected static string $resource = ActionEndingResource::class;

    public ?int $action_id = null;

    public ?Action $actionModel = null;

    public function mount(): void
    {
        parent::mount();
        $this->action_id = request()->route('action');
        $this->actionModel = Action::findOrFail($this->action_id);
    }

    protected function handleRecordCreation(array $data): ActionEnding
    {
        $ending = ActionEnding::create([
            'action_id' => $this->action_id,
            'real_impact' => $data['real_impact'],
            'result' => $data['result'],
            'extemporaneous_reason' => $data['extemporaneous_reason'] ?? null,
            'finished' => true,
            'finished_date' => today(),
            'estimated_evaluation_date' => $data['estimated_evaluation_date'] ?? null,
        ]);

        if (! empty($data['path']) && is_array($data['path'])) {
            app(FileService::class)->createFiles($ending, $data);
        }

        app(ActionEndingService::class)->changeActionStatusToFinish($ending);

        return $ending;
    }

    protected function getRedirectUrl(): string
    {
        return ActionResource::getUrl('ending.view', [
            'action' => $this->action_id,
            'record' => $this->record->id,
        ]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        return $this->actionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            ActionResource::getUrl('view', ['record' => $this->action_id]) => 'Action',
            ActionResource::getUrl('ending.create', ['action' => $this->action_id]) => 'Ending',
            false => 'Create',
        ];
    }
}
