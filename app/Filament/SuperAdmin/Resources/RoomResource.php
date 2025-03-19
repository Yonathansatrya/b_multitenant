<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use App\Models\Room;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\RoomResource\Pages;
use App\Filament\SuperAdmin\Resources\RoomResource\RelationManagers;
use App\Models\Organization;

class RoomResource extends Resource
{
    protected static ?string $navigationLabel = 'Ruangan';
    protected static ?string $navigationGroup = 'Ruangan';
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
                Forms\Components\Select::make('organization_id')
                    ->label('Milik Organisasi')
                    ->options(Organization::all()->pluck('name' , 'id'))
                    ->searchable()
                    ->required(),
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
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Milik Organisasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_description')
                    ->label('Deskripsi Ruangan')
                    ->searchable()
                    ->limit(20),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
