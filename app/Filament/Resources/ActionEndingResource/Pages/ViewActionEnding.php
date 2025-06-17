<?php

namespace App\Filament\Resources\ActionEndingResource\Pages;

use App\Filament\Resources\ActionEndingResource;
use App\Models\Action;
use Filament\Actions\Action as FilamentAction;
use Filament\Resources\Pages\ViewRecord;

class ViewActionEnding extends ViewRecord
{
    protected static string $resource = ActionEndingResource::class;

    public ?int $action_id = null;

    public ?Action $ActionModel = null;

    public ?string $ActionModelName = null;

    public ?string $ActionModelResource = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);

        $this->action_id = request()->route('action_id');

        $action = Action::findOrFail($this->action_id);

        $this->ActionModel = $action;

        $this->ActionModelName = ucfirst($action->type->name);

        $this->ActionModelResource = '\\App\\Filament\\Resources\\'.$this->ActionModelName.'Resource';
    }

    protected function getHeaderActions(): array
    {

        return [

            FilamentAction::make('back')
                ->label('Return')
                ->url(fn ($record): string => $this->ActionModelResource::getUrl('view', [
                    'record' => $this->action_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }

    public function getSubheading(): ?string
    {
        return $this->ActionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->ActionModelResource::getUrl('view', ['record' => $this->action_id]) => $this->ActionModelName,
            false => 'View',
        ];
    }
}
