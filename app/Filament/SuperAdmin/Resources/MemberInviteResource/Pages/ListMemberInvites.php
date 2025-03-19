<?php

namespace App\Filament\SuperAdmin\Resources\MemberInviteResource\Pages;

use App\Filament\SuperAdmin\Resources\MemberInviteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberInvites extends ListRecords
{
    protected static string $resource = MemberInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
