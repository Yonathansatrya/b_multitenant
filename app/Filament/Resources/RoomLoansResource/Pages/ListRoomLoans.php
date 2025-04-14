<?php

namespace App\Filament\Resources\RoomLoansResource\Pages;

use App\Filament\Resources\RoomLoansResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomLoans extends ListRecords
{
    protected static string $resource = RoomLoansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
