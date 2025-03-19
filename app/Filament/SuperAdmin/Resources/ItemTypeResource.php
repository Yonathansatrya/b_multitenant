<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\TypeItem;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Organization;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\ItemTypeResource\Pages;
use App\Filament\SuperAdmin\Resources\ItemTypeResource\RelationManagers;

class ItemTypeResource extends Resource
{
    protected static ?string $navigationLabel = 'Tipe Barang';
    protected static ?string $navigationGroup = 'Barang';
    protected static ?string $model = TypeItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->nullable(),
                Forms\Components\Select::make('organization_id')
                    ->label('Organization')
                    ->options(Organization::all()->pluck('name', 'id'))
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
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListItemTypes::route('/'),
            'create' => Pages\CreateItemType::route('/create'),
            'edit' => Pages\EditItemType::route('/{record}/edit'),
        ];
    }
}
