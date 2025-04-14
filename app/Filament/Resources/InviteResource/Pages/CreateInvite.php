<?php

namespace App\Filament\Resources\InviteResource\Pages;

use App\Filament\Resources\InviteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteMemberMail;

class CreateInvite extends CreateRecord
{
    protected static string $resource = InviteResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['invite_code'] = Str::random(8);
        $data['expires_at'] = now()->addDays(7);

        return $data;
    }

    protected function afterSave(): void
    {
        Mail::to($this->record->user->email)->send(new InviteMemberMail(url("/invite/{$this->record->invite_code}")));
    }
}
