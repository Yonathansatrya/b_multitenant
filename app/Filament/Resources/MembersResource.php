<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MembersResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MembersResource\RelationManagers;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Filament\Forms\Components\TextInput;

class MembersResource extends Resource
{
    protected static ?string $tenantRelationName = 'members';
    protected static ?string $model = OrganizationUser::class;
    protected static ?string $label = 'Member';
    protected static ?string $navigationGroup = "Organizations";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::query()->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->required(),
                Forms\Components\Select::make('role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organization')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([])
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMembers::route('/create'),
            'edit' => Pages\EditMembers::route('/{record}/edit'),
        ];
    }
}
