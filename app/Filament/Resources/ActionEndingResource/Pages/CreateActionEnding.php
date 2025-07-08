<?php

namespace App\Filament\Resources\ActionEndingResource\Pages;

use App\Filament\Resources\ActionEndingResource;
use App\Models\ActionEnding;
use App\Services\ActionStatusService;
use App\Traits\HasActionContext;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateActionEnding extends CreateRecord
{
    use HasActionContext;

    protected static string $resource = ActionEndingResource::class;

    public function mount(): void
    {
        parent::mount();
        $this->loadActionContext();
    }

    protected function handleRecordCreation(array $data): ActionEnding
    {
        $ending = ActionEnding::create([
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

        app(ActionStatusService::class)->statusChangesInActions($this->actionModel, 'finished');
        app(ActionStatusService::class)->closingDateInActions($this->actionModel);

        return $ending;
    }

    protected function getRedirectUrl(): string
    {
        return $this->actionModel->getFilamentUrl();
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
            $this->actionModel->getFilamentUrl() => ucfirst($this->actionModel->type->name),
            false => 'Completion',
        ];
    }
}
