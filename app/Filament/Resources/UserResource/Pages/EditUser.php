<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
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
        $newLeaderOfIds = $this->data['leaderOf'];

        if (! empty($newLeaderOfIds)) {
            DB::table('users_lead_subprocesses')->whereIn('sub_process_id', $newLeaderOfIds)->delete();
        }

        return true;
    }
}
