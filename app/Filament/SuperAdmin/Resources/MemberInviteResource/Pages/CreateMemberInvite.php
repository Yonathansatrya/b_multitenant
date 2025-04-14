<?php

namespace App\Filament\SuperAdmin\Resources\MemberInviteResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use App\Mail\InviteMemberMail;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\SuperAdmin\Resources\MemberInviteResource;

class CreateMemberInvite extends CreateRecord
{
    protected static string $resource = MemberInviteResource::class;

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
