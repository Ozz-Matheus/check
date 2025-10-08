<?php

namespace App\Filament\Resources\AuditControlResource\Pages;

use App\Filament\Resources\AuditControlResource;
use App\Filament\Resources\InternalAuditResource;
use App\Models\AuditLevel;
use App\Services\AuditControlService;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditControl extends ViewRecord
{
    protected static string $resource = AuditControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('qualify')
                ->label(__('Qualify'))
                ->button()
                ->color('primary')
                // 📌 Falta la autorización
                ->visible(fn ($record) => $record->qualified === false)
                // ->requiresConfirmation()
                ->form([
                    Grid::make(2)
                        ->schema([
                            Toggle::make('observation')
                                ->label(__('Without observation'))
                                ->reactive()
                                ->columnSpanFull()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    if ($get('observation') === true) {
                                        $sinOb = AuditLevel::where('min', '=', 0)->where('max', '=', 0)->first()->id;
                                        $set('level_id', $sinOb);
                                        $set('impact_id', null);
                                        $set('probability_id', null);
                                        $set('classification_id', null);
                                        $set('content', null);
                                    } else {
                                        $set('level_id', null);
                                    }
                                }),
                            Select::make('impact_id')
                                ->label(__('Impact'))
                                ->relationship(
                                    name: 'impact',
                                    titleAttribute: 'title',
                                    modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc'),
                                )
                                ->native(false)
                                ->required()
                                ->reactive()
                                ->visible(fn (Get $get) => $get('observation') === false)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $impactId = $get('impact_id') ?? null;
                                    $probabilityId = $get('probability_id') ?? null;

                                    if ($impactId && $probabilityId) {
                                        $auditService = app(AuditControlService::class)->calculatedLevel($impactId, $probabilityId);
                                        $set('level_id', $auditService);
                                    } else {
                                        $set('level_id', null);
                                    }
                                }),
                            Select::make('probability_id')
                                ->label(__('Probability'))
                                ->relationship(
                                    name: 'probability',
                                    titleAttribute: 'title',
                                    modifyQueryUsing: fn ($query) => $query->orderBy('id', 'asc'),
                                )
                                ->native(false)
                                ->required()
                                ->reactive()
                                ->visible(fn (Get $get) => $get('observation') === false)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    $impactId = $get('impact_id') ?? null;
                                    $probabilityId = $get('probability_id') ?? null;

                                    if ($impactId && $probabilityId) {
                                        $auditService = app(AuditControlService::class)->calculatedLevel($impactId, $probabilityId);
                                        $set('level_id', $auditService);
                                    } else {
                                        $set('level_id', null);
                                    }
                                }),
                            Select::make('level_id')
                                ->label(__('Level'))
                                ->relationship('level', 'title')
                                ->required()
                                ->disabled()
                                ->dehydrated(true),
                            Select::make('classification_id')
                                ->label(__('Classification'))
                                ->relationship('classification', 'title')
                                ->native(false)
                                ->required()
                                ->visible(fn (Get $get) => $get('observation') === false),
                            Textarea::make('content')
                                ->label(__('Observations'))
                                ->columnSpanFull()
                                ->visible(fn (Get $get) => $get('observation') === false),
                        ]),
                ])
                ->action(function (array $data) {
                    if ($data['observation'] === true) {
                        $data['impact_id'] = null;
                        $data['probability_id'] = null;
                        $data['classification_id'] = null;
                        $data['content'] = null;
                    }
                    if ($this->record->qualified === false) {
                        $data['qualified'] = true;
                    }
                    // dd($data);
                    $this->record->update($data);
                    app(AuditControlService::class)->calculatedAuditItemLevel($this->record->auditItem);
                    redirect(InternalAuditResource::getUrl('control.view', [
                        'auditItem' => $this->record->audit_item_id,
                        'record' => $this->record->id,
                    ]));
                }),
            Action::make('back')
                ->label(__('Return'))
                ->url(fn ($record): string => InternalAuditResource::getUrl('audit-item.view', [
                    'internalAudit' => $record->auditItem->internal_audit_id,
                    'record' => $record->audit_item_id,
                ]))
                ->button()
                ->color('gray'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            InternalAuditResource::getUrl('view', ['record' => $this->record->auditItem->internal_audit_id]) => __('Internal Audit'),
            InternalAuditResource::getUrl('audit-item.view', ['internalAudit' => $this->record->auditItem->internal_audit_id, 'record' => $this->record->audit_item_id]) => __('Audit Item'),
            InternalAuditResource::getUrl('control.view', ['auditItem' => $this->record->audit_item_id, 'record' => $this->record->id]) => __('Audit Control'),
            false => __('View'),
        ];
    }
}
