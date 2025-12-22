<?php

namespace App\Filament\Resources\DocVersionResource\Pages;

use App\Filament\Resources\DocResource;
use App\Filament\Resources\DocVersionResource;
use App\Models\DocVersion;
use App\Models\Status;
use App\Services\VersionService;
use App\Traits\HasDocContext;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateDocVersion extends CreateRecord
{
    use HasDocContext;

    protected static string $resource = DocVersionResource::class;

    public function mount(): void
    {
        parent::mount();
        $this->loadDocContext();
    }

    protected function handleRecordCreation(array $data): DocVersion
    {
        $leadsIds = $data['leads'] ?? [];
        unset($data['leads']);

        $path = $data['path'];
        $name = $data['name'];

        $disk = config('uploads.disk');

        $sha256 = hash('sha256', Storage::disk($disk)->path($path));

        $data['sha256_hash'] = $sha256;

        $data['doc_id'] = $this->doc_id;

        $validated = app(VersionService::class)->validatedData($data);

        $version = DocVersion::create($validated);

        $fileMetadata = [

            'name' => $name,
            'path' => $path,
            'mime_type' => Storage::disk($disk)->mimeType($path),
            'size' => Storage::disk($disk)->size($path),

        ];

        $version->file()->create($fileMetadata);

        if (! empty($leadsIds)) {

            $pendingStatus = Status::byContextAndTitle('doc', 'pending');

            $pivotData = [
                'status_id' => $pendingStatus->id,
                'comment' => __('Pending version '), // Acá podemos poner un comentario inicial.
            ];

            $version->leads()->attach($leadsIds, $pivotData);
        }

        return $version;
    }

    protected function getRedirectUrl(): string
    {
        return DocResource::getUrl('versions.index', ['doc' => $this->doc_id]);
    }

    public static function canCreateAnother(): bool
    {
        return false;
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('context')
                ->label($this->docModel?->getContextPath())
                ->icon('heroicon-o-information-circle')
                ->disabled()
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
            DocResource::getUrl('index') => __('Documents'),
            DocResource::getUrl('versions.index', ['doc' => $this->doc_id]) => __('Versions'),
            false => __('Create'),
        ];
    }
}
