<?php

namespace App\Filament\Exports;

use App\Models\Items;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ItemExporter extends Exporter
{
    protected static ?string $model = Items::class;
    // protected $fillable = ['name', 'item_type_id', 'stock', 'organization_id'];
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
            ->label('ID'),
            ExportColumn::make('name')
                ->label('nama'),
            ExportColumn::make('item_type_id')
                ->label('tipe item'),
            ExportColumn::make('stock')
                ->label('stock'),
            ExportColumn::make('organization_id')
                ->label('Organisasi'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your item export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
