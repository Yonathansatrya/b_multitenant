<?php

namespace App\Filament\SuperAdmin\Resources\ItemTypeResource\Pages;

use App\Filament\SuperAdmin\Resources\ItemTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemType extends CreateRecord
{
    protected static string $resource = ItemTypeResource::class;
}
