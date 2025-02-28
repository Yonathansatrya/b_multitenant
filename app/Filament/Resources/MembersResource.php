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

class MembersResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'organization';
    protected static ?string $model = User::class;
    protected static ?string $label = 'Member Group';
    protected static ?string $navigationGroup = "Organizations";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('organization');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(
                        User::all()->mapWithKeys(function ($user) {
                            return [$user->id => $user->name];
                        })
                    )
                    ->required(),
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        Role::all()->mapWithKeys(function ($role) {
                            return [$role->name => $role->name];
                        })
                    ])
                    ->required(),
                Forms\Components\TextInput::make('email'),
                // Forms\Components\TextInput::Select('status')
                //     ->label('Status')
                //     ->options([
                //         'active' => 'Active',
                //         'inactive' => 'Inactive',
                //     ])
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->whereHas('organizations', function($q) {
                    $q->where('organization_id', auth()->user()->current_organization_id);
                })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('organizations.pivot.role')
                    ->label('Role')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
