<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActionResource\Pages;
use App\Models\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    public static function form(Form $form): Form
    {
        $livewire = $form->getLivewire();

        $type = property_exists($livewire, 'typeAction')
            ? $livewire->typeAction
            : null;

        return $form->schema(
            Action::getDynamicSchema($type)
        );
    }

    public static function getPages(): array
    {
        return [
            'create' => Pages\CreateAction::route('/{finding}/create'),
            'action_endings.create' => \App\Filament\Resources\ActionEndingResource\Pages\CreateActionEnding::route('/{action_id}/endings/create'),
            'action_endings.view' => \App\Filament\Resources\ActionEndingResource\Pages\ViewActionEnding::route('/{action_id}/endings/{record}'),
            'action_tasks.create' => \App\Filament\Resources\ActionTaskResource\Pages\CreateActionTask::route('/{action_id}/tasks/create'),
            'action_tasks.view' => \App\Filament\Resources\ActionTaskResource\Pages\ViewActionTask::route('/{action_id}/tasks/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
