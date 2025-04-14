<?php

namespace App\Filament\SuperAdmin\Resources\RoomBookingResource\Pages;

use App\Filament\SuperAdmin\Resources\RoomBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomBookings extends ListRecords
{
    protected static string $resource = RoomBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
