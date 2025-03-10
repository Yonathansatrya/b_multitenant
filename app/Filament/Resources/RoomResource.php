<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Room;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoomResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoomResource\RelationManagers;
use Filament\Tables\Filters\SelectFilter;

class RoomResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'organization';
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_code')
                    ->label('Code Room')
                    ->default(fn() => 'R-' . strtoupper(uniqid()))
                    ->required()
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditRoom),
                Forms\Components\TextInput::make('room_name')
                    ->label('Nama Ruangan')
                    ->required(),
                Forms\Components\TextInput::make('room_description')
                    ->label('deskripsi')
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('Status Ruangan')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default('Active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room_code')
                    ->label(' Room Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_name')
                    ->label('Nama Ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_description')
                    ->label('Deskripsi Ruangan')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('status')
                    ->label('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
