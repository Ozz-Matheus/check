<?php

namespace App\Filament\Resources\ActionEndingResource\Pages;

use App\Filament\Resources\ActionEndingResource;
use App\Models\Action;
use App\Services\ActionStatusService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CreateActionEnding extends CreateRecord
{
    protected static string $resource = ActionEndingResource::class;

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
        $ending = static::getModel()::create([
            'real_impact' => $data['real_impact'],
            'result' => $data['result'],
            'action_id' => $this->action_id,
        ]);

        if (! empty($data['path']) && is_array($data['path'])) {

            foreach ($data['path'] as $path) {
                $ending->files()->create([
                    'name' => $data['name'][$path] ?? basename($path),
                    'path' => $path,
                    'mime_type' => Storage::disk('public')->mimeType($path),
                    'size' => Storage::disk('public')->size($path),
                ]);
            }

        }

        app(ActionStatusService::class)->statusChangesInActions($this->ActionModel, 'finished');
        app(ActionStatusService::class)->closingDateInActions($this->ActionModel);

        return $ending;
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
            false => 'Completion',
        ];
    }
}
