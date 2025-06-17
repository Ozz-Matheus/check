<?php

namespace App\Filament\Resources\DocVersionResource\Pages;

use App\Filament\Resources\DocResource;
use App\Filament\Resources\DocVersionResource;
use App\Models\DocVersion;
use App\Services\VersionStatusService;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;

class ChangeVersionStatus extends Page
{
    protected static string $resource = DocVersionResource::class;

    protected static string $view = 'filament::pages.actions';

    public function mount(): void
    {
        $version = DocVersion::findOrFail(request()->route('version'));

        $action = Str::afterLast(request()->route()->getName(), '.');

        if (! method_exists(VersionStatusService::class, $action)) {
            abort(404);
        }

        app(VersionStatusService::class)->$action($version);

        redirect()->to(DocResource::getUrl('versions.index', [
            'doc' => $version->doc_id,
        ]));
    }
}
