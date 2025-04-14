<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use App\Models\Loan;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\ItemLoans;
use Filament\Tables\Table;
use App\Models\Organization;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\ItemLoansResource\Pages;
use App\Filament\SuperAdmin\Resources\ItemLoansResource\RelationManagers;

class ItemLoansResource extends Resource
{
    protected static ?string $navigationLabel = 'Peminjaman Barang';
    protected static ?string $navigationGroup = 'Barang';
    protected static ?string $model = Loan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->reactive(),
                Select::make('organization_loan')
                    ->label('Organization')
                    ->options(
                        fn(callable $get) =>
                        Organization::whereHas(
                            'organizationUsers',
                            fn($query) =>
                            $query->where('user_id', $get('user_id'))
                        )->pluck('name', 'id')
                    )
                    ->required()
                    ->reactive()
                    ->disabled(fn(callable $get) => empty($get('user_id'))),
                DatePicker::make('loan_date')
                    ->label('Tangggal Mulai')
                    ->required(),
                DatePicker::make('loan_end_date')
                    ->label('Tanggal Selesai')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'loans' => 'Pinjam',
                        'returned' => 'Kemabali',
                    ])
                    ->default('loans')
                    ->required(),
                Forms\Components\Select::make('organization_id')
                    ->label('Di buat oleh Organisasi')
                    ->relationship('organization', 'name')
                    ->required(),
                TableRepeater::make('loanItems')
                    ->label('Barang')
                    ->relationship('loanItems')
                    ->headers([
                        Header::make('item_id')->label('Pilih Barang'),
                        Header::make('quantity')->label('Jumlah'),
                    ])
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('item_id')
                                    ->label('Pilih Barang')
                                    ->relationship('item', 'name')
                                    ->required(),

                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required(),
                            ])
                    ])
                    ->columnSpanFull()
                    ->minItems(1)
                    ->defaultItems(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->label('Nama Peminjam'),
                Tables\Columns\TextColumn::make('organizationLoan.name')
                    ->label('Organization Peminjam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('loanItems.quantity')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('loan_date')
                    ->label('Tanggal Pinjam')
                    ->date(),
                Tables\Columns\TextColumn::make('loan_end_date')
                    ->label('Tanggal Kembali')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'loans' => 'danger',
                        'returned' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Return')
                    ->action(fn(Loan $record) => $record->update(['status' => 'returned']))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListItemLoans::route('/'),
            'create' => Pages\CreateItemLoans::route('/create'),
            'edit' => Pages\EditItemLoans::route('/{record}/edit'),
        ];
    }
}
