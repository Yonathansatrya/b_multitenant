<?php

namespace App\Filament\Resources\RoomLoansResource\Pages;

use Filament\Actions;
use App\Models\RoomLoans;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RoomLoansResource;

class EditRoomLoans extends EditRecord
{
    protected static string $resource = RoomLoansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (!isset($data['loan_code']) || !preg_match('/^\d{8}-\d{3}$/', $data['loan_code'])) {
            $datePrefix = now()->format('Ymd');

            $lastLoan = RoomLoans::where('loan_code', 'LIKE', "{$datePrefix}-%")
                ->orderBy('loan_code', 'desc')
                ->first();

            $nextNumber = $lastLoan
                ? (intval(substr($lastLoan->loan_code, -3)) + 1)
                : 1;

            $data['loan_code'] = "{$datePrefix}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $data;
    }
}
