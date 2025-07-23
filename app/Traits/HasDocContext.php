<?php

namespace App\Traits;

use App\Models\Doc;
use App\Models\Status;
use Filament\Notifications\Notification;

trait HasDocContext
{
    public ?Doc $docModel = null;

    public ?string $doc_id = null;

    public function loadDocContext(): void
    {
        $this->doc_id = request()->route('doc');

        $doc = Doc::findOrFail($this->doc_id);

        $user = auth()->user();

        if (! $user->canAccessSubProcess($doc->sub_process_id)) {
            abort(403);
        }

        $this->docModel = $doc;
    }

    protected function checkVersionStatusNotice(): void
    {
        if (! session()->has('version_status')) {
            return;
        }

        $data = session('version_status');

        $status = Status::byContextAndTitle('doc', $data['status_title']);

        Notification::make()
            ->title('Version successfully '.$status->label)
            ->icon($status->iconName())
            ->color($status->colorName())
            ->status($status->colorName())
            ->send();
    }
}
