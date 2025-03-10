<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Room;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\RoomLoans;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions;
use Filament\Facades\Filament;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoomLoansResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoomLoansResource\RelationManagers;

class RoomLoansResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'organization';
    protected static ?string $model = RoomLoans::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('loan_code')
                    ->label('Loan Code')
                    ->required()
                    ->default(fn($livewire) => $livewire instanceof Pages\CreateRoomLoans
                        ? now()->format('Ymd') . '-001'
                        : null)
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditRoomLoans),
                Forms\Components\TextInput::make('loan_name')
                    ->label('Nama Peminjam')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\Select::make('loan_status')
                    ->label('Status')
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
                    ->label('Room List')
                    ->relationship('roomLoanDetails')
                    ->headers([
                        Header::make('room_id')->label('Pilih Ruangan'),
                        Header::make('note')->label('Note'),
                    ])
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('room_id')
                                    ->label('Room')
                                    ->options(fn() => Room::where('status', 'Active')->pluck('room_name', 'id')->toArray())
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('note')
                                    ->label('Note')
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
                    ->label('Loan Code')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Approve')
                    ->visible(fn() => auth()->user()->hasRole('Super Admin'))
                    ->action(fn(RoomLoans $record) => $record->update(['loan_status' => 'Approve']))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Approve Selected')
                        ->visible(fn() => auth()->user()->hasRole('Super Admin'))
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn(array $records) => RoomLoans::whereIn('id', $records)->update(['loan_status' => 'Approve']))
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Selected loans have been approved!'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomLoans::route('/'),
            'create' => Pages\CreateRoomLoans::route('/create'),
            'edit' => Pages\EditRoomLoans::route('/{record}/edit'),
        ];
    }
}
