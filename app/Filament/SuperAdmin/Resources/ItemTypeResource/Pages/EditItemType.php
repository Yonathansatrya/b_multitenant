<?php

namespace App\Filament\SuperAdmin\Resources\ItemTypeResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemType extends EditRecord
{
    protected static string $resource = ItemTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
