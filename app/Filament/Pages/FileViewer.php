<?php

namespace App\Filament\Pages;

use App\Filament\Resources\DocResource;
use App\Models\Doc;
use App\Models\DocVersion;
use App\Models\File;
use App\Support\AppNotifier;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class FileViewer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.file-viewer';

    public ?File $file = null;

    public ?Doc $doc = null;

    public function getTitle(): string
    {
        return __('File Viewer');
    }

    public function mount(): void
    {
        $this->file = File::findOrFail(request('file'));

        if ($this->file->fileable_type === DocVersion::class) {

            $docVersion = DocVersion::findOrFail($this->file->fileable_id);

            $this->doc = Doc::findOrFail($docVersion->doc_id);

            $user = auth()->user();

            if ($this->doc->display_restriction) {

                $hasAccess = DB::table('docs_has_confidential_users')
                    ->where('doc_id', $this->doc->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (! $hasAccess && ! $user->canAccessSubProcess($this->doc->sub_process_id)) {

                    AppNotifier::error(__('You do not have permission to view this document.'));
                    abort(403);
                }
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Return'))
                ->url(fn (): string => DocResource::getUrl('versions.index', ['doc' => $this->doc]))
                ->button()
                ->color('gray'),

        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            DocResource::getUrl('index') => __('Documents'),
            DocResource::getUrl('versions.index', ['doc' => $this->doc->id]) => __('Versions'),
            false => __('File Viewer'),
        ];
    }

    public function getSubheading(): ?string
    {
        return $this->file->name;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
