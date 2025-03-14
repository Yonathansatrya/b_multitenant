<?php

namespace App\Filament\Resources\RoomResource\Pages;

use App\Models\Room;
use App\Filament\Resources\RoomResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $latestNumber = Room::where('room_code', 'LIKE', 'R-%')
            ->selectRaw("MAX(CAST(SUBSTRING(room_code, 3, LENGTH(room_code) - 2) AS UNSIGNED)) as max_code")
            ->value('max_code');

        $nextNumber = ($latestNumber ?? 0) + 1;

        $data['room_code'] = 'R-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return $data;
    }
}
