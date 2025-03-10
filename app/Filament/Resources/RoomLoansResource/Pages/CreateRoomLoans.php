<?php

namespace App\Filament\Resources\RoomLoansResource\Pages;

use Filament\Actions;
use App\Models\RoomLoans;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\RoomLoansResource;

class CreateRoomLoans extends CreateRecord
{
    protected static string $resource = RoomLoansResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $datePrefix = now()->format('Ymd');

        $lastLoan = RoomLoans::where('loan_code', 'LIKE', "{$datePrefix}-%")
            ->orderBy('loan_code', 'desc')
            ->first();

        $nextNumber = $lastLoan
            ? (intval(substr($lastLoan->loan_code, -3)) + 1)
            : 1;
        $data['loan_code'] = "{$datePrefix}" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return $data;
    }
}
