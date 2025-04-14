<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Items;
use App\Models\TypeItem;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\ItemResource\Pages;
use App\Filament\SuperAdmin\Resources\ItemResource\RelationManagers;

class ItemResource extends Resource
{
    protected static ?string $navigationLabel = 'Barang';
    protected static ?string $navigationGroup = 'Barang';
    protected static ?string $model = Items::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\Select::make('item_type_id')
                    ->label('Tipe Barang')
                    ->options(TypeItem::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('stock')
                    ->label('Jumlah Stok')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('organization_id')
                    ->label('Organization')
                    ->options(\App\Models\Organization::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('typeItem.name')
                    ->label('Tipe Barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Jumlah Stok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organization')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
