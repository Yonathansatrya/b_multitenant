<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\TypeItem;
use Filament\Forms\Form;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\TypeItemExporter;
use App\Filament\Imports\ItemTypeImporter;
use App\Filament\Resources\TypeItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TypeItemResource\RelationManagers;

class TypeItemResource extends Resource
{
    protected static ?string $navigationGroup = 'Barang';
    protected static ?string $navigationLabel = 'Tipe Barang';
    protected static ?string $model = TypeItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Tipe Barang')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('deskripsi Tipe')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tipe Barang')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('deskripsi')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->HeaderActions([
                Tables\Actions\ImportAction::make('Import Tipe Item')
                    ->label('Import')
                    ->icon('heroicon-o-inbox-arrow-down')
                    ->color('success')
                    ->importer(ItemTypeImporter::class),
                Tables\Actions\ExportAction::make()
                    ->label('Export')
                    ->icon('heroicon-o-document')
                    ->color('danger')
                    ->exporter(TypeItemExporter::class),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\ExportBulkAction::make()
                    ->label('Export')
                    ->icon('heroicon-o-document')
                    ->color('danger')
                    ->exporter(TypeItemExporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTypeItems::route('/'),
            'create' => Pages\CreateTypeItem::route('/create'),
            'edit' => Pages\EditTypeItem::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) ?? false;
    }
}
