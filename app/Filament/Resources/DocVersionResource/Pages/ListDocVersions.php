<?php

namespace App\Filament\Resources\DocVersionResource\Pages;

use App\Filament\Resources\DocResource;
use App\Filament\Resources\DocVersionResource;
use App\Models\Doc;
use App\Models\Status;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListDocVersions extends ListRecords
{
    protected static string $resource = DocVersionResource::class;

    public $docModel = null;

    public ?string $doc_id = null;

    public function mount(): void
    {
        parent::mount();

        $this->doc_id = request()->route('doc');

        $doc = Doc::findOrFail($this->doc_id);

        $this->docModel = $doc;

        $user = auth()->user();

        abort_if(! $user->canAccessSubProcess($doc->sub_process_id), 403);

        if (session()->has('version_status')) {

            $data = session('version_status');

            $status = Status::byTitle($data['status_title']);

            Notification::make()
                ->title('Version successfully '.$status->label)
                ->icon($status->iconName())
                ->color($status->colorName())
                ->status($status->colorName())
                ->send();
        }
    }

    public function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getTableQuery();

        if ($this->doc_id) {
            $query->where('doc_id', $this->doc_id);

            if (! $this->tableSortColumn) {
                $query->orderByDesc('version');
            }
        }

        return $query;
    }

    protected function getHeaderActions(): array
    {
        if (! $this->doc_id) {
            return [];
        }

        return [

            Action::make('context')
                ->label($this->docModel?->getContextPath())
                ->icon('heroicon-o-information-circle')
                ->disabled()
                ->color('gray'),

            Action::make('addFile')
                ->label(__('Upload file'))
                ->button()
                ->authorize(fn ($record) => auth()->user()->can('create_doc::version', $record))
                ->url(fn (): string => DocResource::getUrl('versions.create', [
                    'doc' => $this->doc_id,
                ])),
            Action::make('back')
                ->label(__('Return'))
                ->url(fn (): string => DocResource::getUrl('index'))
                ->button()
                ->color('gray'),

        ];
    }

    public function getSubheading(): ?string
    {
        return $this->docModel?->title;
    }

    public function getBreadcrumbs(): array
    {
        return [
            DocResource::getUrl('index') => 'Documents',
            DocResource::getUrl('versions.index', ['doc' => $this->doc_id]) => 'Versions',
            false => 'List',
        ];
    }
}
