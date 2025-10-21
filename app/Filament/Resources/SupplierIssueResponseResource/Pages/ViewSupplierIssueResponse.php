<?php

namespace App\Filament\Resources\SupplierIssueResponseResource\Pages;

use App\Filament\Resources\SupplierIssueResource;
use App\Filament\Resources\SupplierIssueResponseResource;
use App\Filament\Resources\SupplierPortalResource;
use App\Models\Status;
use App\Models\SupplierIssue;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewSupplierIssueResponse extends ViewRecord
{
    protected static string $resource = SupplierIssueResponseResource::class;

    public ?int $supplier_issue_id = null;

    public ?SupplierIssue $supplierIssueModel = null;

    public function mount(string|int $record): void
    {
        parent::mount($record);
        $this->supplier_issue_id = request()->route('supplier_issue');
        $supplierIssueModel = SupplierIssue::findOrFail($this->supplier_issue_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('qualification_effectiveness')
                ->label(__('Qualify effectiveness'))
                ->form([
                    /* Grid::make(2)->schema([
                    ]), */
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
                ])
                ->authorize(! auth()->user()->hasRole('supplier'))
                ->visible(fn ($record): bool => ! filled($record->effectiveness))
                ->action(function (array $data) {
                    $data['evaluation_date'] = today();
                    $this->record->update($data);
                    $this->record->supplierIssue()->update([
                        'status_id' => Status::byContextAndTitle('supplier_issue', 'closed')?->id,
                    ]);
                    $this->refreshFormData([
                        'effectiveness',
                        'evaluation_comment',
                        'evaluation_date',
                    ]);
                }),
            Action::make('back')
                ->label(__('Return'))
                ->url(function ($record): string {
                    if (auth()->user()->hasRole('supplier')) {
                        return SupplierPortalResource::getUrl('view', ['record' => $record->supplier_issue_id]);
                    }

                    return SupplierIssueResource::getUrl('view', ['record' => $record->supplier_issue_id]);
                })
                ->color('gray'),
        ];
    }
}
