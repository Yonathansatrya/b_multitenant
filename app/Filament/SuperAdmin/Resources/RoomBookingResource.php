<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use App\Models\Room;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\RoomLoans;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\RoomBookingResource\Pages;
use App\Filament\SuperAdmin\Resources\RoomBookingResource\RelationManagers;
use App\Models\Organization;

class RoomBookingResource extends Resource
{
    protected static ?string $navigationLabel = 'Booking Ruangan';
    protected static ?string $navigationGroup = 'Ruangan';
    protected static ?string $model = RoomLoans::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->label('Peminjaman Milik Organisasi')
                    ->options(Organization::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('loan_code')
                    ->label('Kode Peminjaman')
                    ->required()
                    ->default(fn($livewire) => $livewire instanceof Pages\CreateRoomBooking
                        ? now()->format('Ymd') . '-' . str_pad(
                            (RoomLoans::whereDate('created_at', now()->toDateString())->count() + 1),
                            3,
                            '0',
                            STR_PAD_LEFT
                        )
                        : null)
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditRoomBooking),
                Forms\Components\TextInput::make('loan_name')
                    ->label('Nama Peminjam')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\Radio::make('loan_status')
                    ->label('Status')
                    ->inline()
                    ->options([
                        'Pending' => 'Pending',
                        'Reject' => 'Reject',
                        'Approve' => 'Approve',
                        'Finish' => 'Finish',
                    ])
                    ->default('Pending')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required(),
                TableRepeater::make('roomLoanDetails')
                    ->label('Daftar Ruangan')
                    ->relationship('roomLoanDetails')
                    ->headers([
                        Header::make('room_id')->label('Ruangan')->align(Alignment::Center),
                    ])
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('room_id')
                                    ->label('Pilih Ruangan')
                                    ->options(
                                        fn() => Room::where('status', 'Active')
                                            ->get()
                                            ->mapWithKeys(fn($room) => [$room->id => "{$room->room_name} ({$room->room_code})"])
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('note')
                                    ->label('Catatan')
                                    ->nullable(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->defaultItems(1)
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('loan_code')
                    ->label('Kode Ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('loan_name')
                    ->label('Nama Peminjam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->searchable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->searchable(),
                Tables\Columns\TextColumn::make('loan_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Reject' => 'danger',
                        'Approve' => 'success',
                        'Finish' => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('loan_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Reject' => 'Reject',
                        'Approve' => 'Approve',
                        'Finish' => 'Finish',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('Finish')
                    ->visible(
                        fn(RoomLoans $record) =>
                        $record->loan_status === 'Approve'
                    )
                    ->action(fn(RoomLoans $record) => $record->update(['loan_status' => 'Finish']))
                    ->requiresConfirmation()
                    ->color('gray')
                    ->icon('heroicon-o-clipboard'),

                Tables\Actions\Action::make('Approve')
                    ->visible(
                        fn(RoomLoans $record) =>
                        $record->loan_status === 'Pending'
                    )
                    ->action(fn(RoomLoans $record) => $record->update(['loan_status' => 'Approve']))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),

                Tables\Actions\Action::make('Reject')
                    ->visible(
                        fn(RoomLoans $record) =>
                        $record->loan_status === 'Pending'
                    )
                    ->action(fn(RoomLoans $record) => $record->update(['loan_status' => 'Reject']))
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\EditAction::make(),
                ])
                    ->iconButton()
                    ->label('Actions')
                    ->visible(fn() => auth()->user()->hasRole('Super Admin')),
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
            'index' => Pages\ListRoomBookings::route('/'),
            'create' => Pages\CreateRoomBooking::route('/create'),
            'edit' => Pages\EditRoomBooking::route('/{record}/edit'),
        ];
    }
}
