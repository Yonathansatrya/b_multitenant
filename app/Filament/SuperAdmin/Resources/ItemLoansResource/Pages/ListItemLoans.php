<?php

namespace App\Filament\SuperAdmin\Resources\ItemLoansResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemLoansResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemLoans extends ListRecords
{
    protected static string $resource = ItemLoansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
