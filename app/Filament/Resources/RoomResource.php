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
    protected static ?string $navigationLabel = 'Ruangan';
    protected static ?string $navigationGroup = 'Ruangan';
    protected static ?string $tenantOwnershipRelationshipName = 'organization';
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('room_code')
                    ->default(
                        fn() => 'R-' . str_pad(
                            (Room::where('room_code', 'REGEXP', '^R-[0-9]+$')
                                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(room_code, '-', -1) AS UNSIGNED)) as max_code")
                                ->value('max_code') ?? 0) + 1,
                            3,
                            '0',
                            STR_PAD_LEFT
                        )
                    )
                    ->label('Kode Ruangan')
                    ->required()
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditRoom),
                Forms\Components\TextInput::make('room_name')
                    ->label('Nama Ruangan')
                    ->required(),
                Forms\Components\TextInput::make('room_description')
                    ->label('deskripsi Ruangan')
                    ->nullable(),
                Forms\Components\Select::make('status')
                    ->label('Status Ruangan')
                    ->options([
                        'Active' => 'Aktif',
                        'Inactive' => 'Tidak Aktif',
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
                    ->label(' Kode Ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_name')
                    ->label('Nama Ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_description')
                    ->label('Deskripsi Ruangan')
                    ->searchable()
                    ->limit(30),
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

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) ?? false;
    }
}
