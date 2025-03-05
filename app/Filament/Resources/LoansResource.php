<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Loan;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Organization;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LoansResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LoansResource\RelationManagers;

class LoansResource extends Resource
{
    protected static ?string $label = 'Peminjaman';
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
                TextInput::make('description')
                    ->required(),
                Select::make('status')
                    ->options([
                        'loans' => 'Pinjam',
                        'returned' => 'Kemabali',
                    ])
                    ->default('loans')
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
                    ->label('Organization')
                    ->searchable(),
                Tables\Columns\TextColumn::make('loanItems.quantity')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('loan_date')
                    ->label('tanggal pinjam')
                    ->date(),
                Tables\Columns\TextColumn::make('loan_end_date')
                    ->label('tanggal kembali')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'loans' => 'gray',
                        'returned' => 'green',
                    ]),
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoans::route('/create'),
            'edit' => Pages\EditLoans::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            //   Widgets\CalenderLoansWidget::class,
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) ?? false;
    }
}
