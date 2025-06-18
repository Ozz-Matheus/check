<?php

namespace App\Filament\Resources\DocVersionResource\Pages;

use App\Filament\Resources\DocResource;
use App\Filament\Resources\DocVersionResource;
use App\Services\VersionService;
use App\Traits\HasDocContext;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
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

    protected function handleRecordCreation(array $data): Model
    {
        $path = $data['path'];
        $name = $data['name'];

        $sha256 = hash('sha256', Storage::disk('public')->path($path));

        $data['sha256_hash'] = $sha256;

        $data['doc_id'] = $this->doc_id;

        $validated = app(VersionService::class)->validatedData($data);

        $version = static::getModel()::create($validated);

        $fileMetadata = [

            'name' => $name,
            'path' => $path,
            'mime_type' => Storage::disk('public')->mimeType($path),
            'size' => Storage::disk('public')->size($path),

        ];

        $version->file()->create($fileMetadata);

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
            DocResource::getUrl('index') => 'Documents',
            DocResource::getUrl('versions.index', ['doc' => $this->doc_id]) => 'Versions',
            false => 'Create',
        ];
    }
}
