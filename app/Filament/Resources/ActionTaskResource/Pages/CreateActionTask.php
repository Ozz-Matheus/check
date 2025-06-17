<?php

namespace App\Filament\Resources\ActionTaskResource\Pages;

use App\Filament\Resources\ActionTaskResource;
use App\Models\Action;
use App\Models\Status;
use App\Services\ActionStatusService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateActionTask extends CreateRecord
{
    protected static string $resource = ActionTaskResource::class;

    public ?int $action_id = null;

    public ?Action $ActionModel = null;

    public ?string $ActionModelName = null;

    public ?string $ActionModelResource = null;

    public function mount(): void
    {
        parent::mount();

        $this->action_id = request()->route('action_id');

        $action = Action::findOrFail($this->action_id);

        $this->ActionModel = $action;

        $this->ActionModelName = ucfirst($action->type->name);

        $this->ActionModelResource = '\\App\\Filament\\Resources\\'.$this->ActionModelName.'Resource';

    }

    protected function handleRecordCreation(array $data): Model
    {

        $task = static::getModel()::create([
            'action_id' => $this->action_id,
            'title' => $data['title'],
            'detail' => $data['detail'],
            'responsible_by_id' => $data['responsible_by_id'],
            'start_date' => $data['start_date'],
            'deadline' => $data['deadline'],
            'status_id' => Status::byContextAndTitle('task', 'pending_task')?->id,
        ]);

        app(ActionStatusService::class)->statusChangesInActions($this->ActionModel, 'in_execution');

        return $task;
    }

    protected function getRedirectUrl(): string
    {
        return $this->ActionModelResource::getUrl('view', ['record' => $this->action_id]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getSubheading(): ?string
    {
        return $this->ActionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->ActionModelResource::getUrl('view', ['record' => $this->action_id]) => $this->ActionModelName,
            false => 'Task',
        ];
    }
}
