<?php

namespace App\Filament\Resources\ActionEndingResource\Pages;

use App\Filament\Resources\ActionEndingResource;
use App\Filament\Resources\ActionResource;
use App\Models\Action;
use App\Services\ActionEndingService;
use Filament\Actions\Action as FilamentAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewActionEnding extends ViewRecord
{
    protected static string $resource = ActionEndingResource::class;

    public ?int $action_id = null;

    public ?Action $actionModel = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);
        $this->action_id = request()->route('action');
        $this->actionModel = Action::findOrFail($this->action_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            FilamentAction::make('qualification_effectiveness')
                ->label(__('Qualify effectiveness'))
                ->form([
                    Select::make('effectiveness')
                        ->label(__('Effectiveness'))
                        ->options([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                            'partial' => __('Partial'),
                        ])
                        ->native(false)
                        ->required(),
                    Textarea::make('evaluation_comment')
                        ->label(__('Evaluation comment'))
                        ->rows(3)
                        ->required(),
                ])
                ->requiresConfirmation()
                ->action(function (array $data) {
                    $data['real_evaluation_date'] = now()->format('Y-m-d');
                    $this->record->update($data);
                    $this->redirect(
                        ActionResource::getUrl('ending.view', [
                            'action' => $this->action_id,
                            'record' => $this->record->id,
                        ]),
                    );
                })
                ->authorize(fn ($record): bool => auth()->user()->can('update', $this->record) && auth()->id() === $record->action->verification_responsible_by_id)
                ->visible(fn ($record): bool => app(ActionEndingService::class)->canViewQualifyAction($record)),
            FilamentAction::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => ActionResource::getUrl('view', [
                    'record' => $record->action_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }

    public function getSubheading(): ?string
    {
        return $this->actionModel->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            ActionResource::getUrl('view', ['record' => $this->action_id]) => 'Action',
            ActionResource::getUrl('ending.view', [
                'action' => $this->action_id,
                'record' => $this->record->id,
            ]) => 'Ending',
            false => 'View',
        ];
    }
}
