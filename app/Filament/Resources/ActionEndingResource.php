<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionEndingResource\Pages;
use App\Filament\Resources\ActionEndingResource\RelationManagers\ActionEndingFilesRelationManager;
use App\Models\ActionEnding;
use App\Models\ActionType;
use App\Models\Status;
use App\Traits\HasStandardFileUpload;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ActionEndingResource extends Resource
{
    use HasStandardFileUpload;

    protected static ?string $model = ActionEnding::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Ending data'))
                    ->description(__('Enter the completion data and upload your supports'))
                    ->schema([
                        Forms\Components\Textarea::make('real_impact')
                            ->label(__('Real impact'))
                            ->required(),
                        Forms\Components\Textarea::make('result')
                            ->label(__('Result'))
                            ->required(),
                        Forms\Components\Textarea::make('extemporaneous_reason')
                            ->label(__('Reason for extemporaneous closing'))
                            ->visible(fn ($livewire, $record) => $livewire->actionModel->status_id === Status::byContextAndTitle('action_and_task', 'overdue')?->id || filled($record?->extemporaneous_reason))
                            ->required(fn ($livewire) => $livewire->actionModel->status_id === Status::byContextAndTitle('action_and_task', 'overdue')?->id),
                        Forms\Components\DatePicker::make('real_closing_date')
                            ->label(__('Real closing date'))
                            ->native(false)
                            ->readOnly()
                            ->visible(fn ($record) => filled($record?->real_closing_date)),
                        Forms\Components\DatePicker::make('estimated_evaluation_date')
                            ->label(__('Estimated evaluation date'))
                            ->minDate(now()->format('Y-m-d'))
                            ->closeOnDateSelection()
                            ->native(false)
                            ->required(fn ($livewire) => $livewire->actionModel?->action_type_id === ActionType::where('name', 'corrective')->first()?->id)
                            ->visible(fn ($livewire) => $livewire->actionModel?->action_type_id === ActionType::where('name', 'corrective')->first()?->id),
                        static::baseFileUpload('path')
                            ->label(__('Support ending files'))
                            ->directory('actions/endings/files')
                            ->multiple()
                            // ->maxParallelUploads(1)
                            ->visible(fn (string $context) => $context === 'create'),
                        Forms\Components\TextInput::make('effectiveness')
                            ->label(__('Effectiveness'))
                            ->visible(fn ($record) => filled($record?->effectiveness))
                            ->readOnly(),
                        Forms\Components\Textarea::make('evaluation_comment')
                            ->label(__('Evaluation comment'))
                            ->visible(fn ($record) => filled($record?->evaluation_comment))
                            ->readOnly(),
                        Forms\Components\DatePicker::make('real_evaluation_date')
                            ->label(__('Real evaluation date'))
                            ->native(false)
                            ->visible(fn ($record) => filled($record?->real_evaluation_date))
                            ->readOnly(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActionEndingFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActionEndings::route('/'),
            'create' => Pages\CreateActionEnding::route('/create'),
            'edit' => Pages\EditActionEnding::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
