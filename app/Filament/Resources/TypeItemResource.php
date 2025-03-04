<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypeItemResource\Pages;
use App\Filament\Resources\TypeItemResource\RelationManagers;
use App\Models\TypeItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypeItemResource extends Resource
{
    protected static ?string $label = 'Tipe Barang';
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
            'index' => Pages\ListTypeItems::route('/'),
            'create' => Pages\CreateTypeItem::route('/create'),
            'edit' => Pages\EditTypeItem::route('/{record}/edit'),
        ];
    }
}
