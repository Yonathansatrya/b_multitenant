<?php

namespace App\Filament\SuperAdmin\Resources\MemberInviteResource\Pages;

use Filament\Forms;
use Filament\Actions;
use App\Models\Invite;
use Illuminate\Support\Str;
use App\Models\Organization;
use Filament\Tables\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\SuperAdmin\Resources\MemberInviteResource;


class ListMemberInvites extends ListRecords
{
    protected static string $resource = MemberInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('generateLink')
                ->label('Generate Link')
                ->icon('heroicon-o-link')
                ->modalHeading('Generate Invitation Link')
                ->modalSubmitActionLabel('Generate Link')
                ->modalWidth('md')
                ->form([
                    Forms\Components\Select::make('organization_id')
                        ->label('Pilih Organisasi')
                        ->options(Organization::query()->pluck('name', 'id')->toArray())
                        ->required(),
                ])
                ->action(function (array $data, Actions\Action $action) {
                    $expiresAt = now()->addDays(7);
                    $organizationId = $data['organization_id'];

                    $invite = Invite::create([
                        'organization_id' => $organizationId,
                        'invite_code' => Str::random(8),
                        'expires_at' => $expiresAt,
                    ]);

                    $inviteLink = url("/invite/{$invite->invite_code}");

                    $action->modalContent(view('invite-link-modal', compact('inviteLink')));
                    return $action->halt();
                }),
        ];
    }
}
