<?php

namespace App\Filament\Resources\InviteResource\Pages;

use App\Filament\Resources\InviteResource;
use Filament\Actions;
use App\Models\Invite;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ListRecords;

class ListInvites extends ListRecords
{
    protected static string $resource = InviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('generateLink')
                ->label('Generate Link')
                ->icon('heroicon-o-link')
                ->action(function (Actions\Action $action) {
                    $user = auth()->user();
                    $organizationId = $user->current_organization_id ?? $user->organizations()->orderBy('created_at')->first()->id ?? null;

                    $inviteCode = Str::random(8);
                    $expiresAt = now()->addDays(7);

                    $invite = Invite::create([
                        'organization_id' => $organizationId,
                        'invite_code' => $inviteCode,
                        'expires_at' => $expiresAt,
                    ]);

                    $inviteLink = url("/invite/{$invite->invite_code}");

                    $action->modalContent(view('invite-link-modal', compact('inviteLink')));
                    return $action->halt();
                }),
        ];
    }
}
