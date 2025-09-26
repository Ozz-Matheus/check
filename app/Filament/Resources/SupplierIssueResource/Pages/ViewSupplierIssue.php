<?php

namespace App\Filament\Resources\SupplierIssueResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use App\Models\Status;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplierIssue extends ViewRecord
{
    protected static string $resource = SupplierIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('qualification_effectiveness')
                ->label(__('Qualify effectiveness'))
                ->form([
                    Grid::make(2)->schema([
                        Textarea::make('supplier_response')
                            ->label(__('Supplier response'))
                            ->rows(3)
                            ->required(),
                        Textarea::make('supplier_actions')
                            ->label(__('Supplier actions'))
                            ->rows(3)
                            ->required(),
                        DatePicker::make('response_date')
                            ->label(__('Response date'))
                            ->maxDate(now())
                            ->closeOnDateSelection()
                            ->native(false)
                            ->required(),
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
                            ->required()
                            ->columnSpanFull(),
                    ]),
                ])
                // 📌 Falta la autorización
                ->visible(fn ($record): bool => ! filled($record->effectiveness))
                ->action(function (array $data) {
                    $data['status_id'] = Status::byContextAndTitle('supplier_issue', 'closed')?->id;
                    $data['real_evaluation_date'] = now()->format('Y-m-d');
                    $this->record->update($data);
                    $this->redirect(static::getResource()::getUrl('view', [
                        'record' => $this->record,
                    ]));
                }),
            Action::make('back')
                ->label(__('Return'))
                ->url($this->getResource()::getUrl('index'))
                ->button()
                ->color('gray'),
        ];
    }
}
