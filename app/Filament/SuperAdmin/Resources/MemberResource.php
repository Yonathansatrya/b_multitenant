<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\MemberResource\Pages;
use App\Filament\SuperAdmin\Resources\MemberResource\RelationManagers;

class MemberResource extends Resource
{
    protected static ?string $navigationLabel = 'Member';
    protected static ?string $navigationGroup = 'Team';
    protected static ?string $model = OrganizationUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(
                        User::all()->mapWithKeys(fn($user) => [$user->id => $user->name])
                    )
                    ->required(),
                Forms\Components\Select::make('organization_id')
                    ->label('Organization')
                    ->options(
                        Organization::all()->mapWithKeys(fn($organization) => [$organization->id => $organization->name])
                    )
                    ->required(),
                Forms\Components\Select::make('role_id')
                    ->label('Role')
                    ->options(
                        \App\Models\Role::all()->mapWithKeys(fn($role) => [$role->id => $role->name])
                    )
                    ->required(),
                Forms\Components\Radio::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->inline()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organization')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
