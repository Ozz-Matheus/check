<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Support\AppNotifier;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(function () {
                    return ! $this->record->hasRole('super_admin');
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeSave(): bool
    {
        $originalLeaderOfIds = $this->record->leaderOf()->pluck('sub_process_id')->toArray();
        $newLeaderOfIds = $this->data['leaderOf'] ?? [];

        $removedLeaderOfIds = array_diff($originalLeaderOfIds, $newLeaderOfIds);
        $conflictSubProcesses = [];

        foreach ($removedLeaderOfIds as $subProcessId) {
            $leaderCount = DB::table('users_lead_subprocesses')
                ->where('sub_process_id', $subProcessId)
                ->count();

            if ($leaderCount <= 1) {
                $subProcess = DB::table('sub_processes')->where('id', $subProcessId)->value('title');
                if ($subProcess) {
                    $conflictSubProcesses[] = $subProcess;
                }
            }
        }

        if (! empty($conflictSubProcesses)) {
            $subProcessesList = implode(', ', $conflictSubProcesses);

            AppNotifier::error(
                __('Acción denegada'),
                __('No puedes dejar los siguientes subprocesos sin al menos un líder: :subprocesses. Asigna uno nuevo antes de retirar a este usuario.', ['subprocesses' => $subProcessesList])
            );

            $this->fillForm();
            $this->halt();
        }

        return true;
    }
}
