<?php

namespace App\Filament\Pages;

use App\Filament\Resources\DocResource;
use App\Models\Doc;
use App\Models\DocVersion;
use App\Models\File;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class FileViewer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.file-viewer';

    public ?File $file = null;

    public ?Doc $doc = null;

    public function mount(): void
    {
        $this->file = File::findOrFail(request('file'));
        if ($this->file->fileable_type === DocVersion::class) {

            $docVersion = DocVersion::findOrFail($this->file->fileable_id);

            $this->doc = Doc::findOrFail($docVersion->doc_id);

            if ($this->doc->display_restriction) {

                $userId = auth()->id();

                $hasAccess = DB::table('docs_has_confidential_users')
                    ->where('doc_id', $this->doc->id)
                    ->where('user_id', $userId)
                    ->exists();

                if (! $hasAccess) {

                    $this->doc::notifyError(__('You do not have permission to view this document.'));
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

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
