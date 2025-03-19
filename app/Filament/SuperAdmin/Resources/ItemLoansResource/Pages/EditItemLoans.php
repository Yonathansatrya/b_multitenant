<?php

namespace App\Filament\SuperAdmin\Resources\ItemLoansResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemLoansResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemLoans extends EditRecord
{
    protected static string $resource = ItemLoansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
