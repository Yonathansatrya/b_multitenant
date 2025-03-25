<?php

namespace App\Filament\Imports;

use App\Models\ItemType;
use App\Models\TypeItem;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ItemTypeImporter extends Importer
{
    protected static ?string $model = TypeItem::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('organization_id')
                ->requiredMapping()
                ->rules(['required', 'exists:organizations,id']),
        ];
    }

    public function resolveRecord(): ?TypeItem
    {
        return new TypeItem();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your item type import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
