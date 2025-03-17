<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Invite;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InviteResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use App\Mail\InviteMemberMail;
use App\Filament\Resources\InviteResource\RelationManagers;

class InviteResource extends Resource
{
    protected static ?string $navigationLabel = 'Member Invite';
    protected static ?string $navigationGroup = 'Organizations';
    protected static ?string $model = Invite::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('users')
                    ->multiple()
                    ->relationship('users', 'name')
                    ->label('User')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('invite_code')
                    ->disabled()
                    ->label('Invite Code'),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->disabled()
                    ->label('Expires At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('users.name')
                    ->label('Users')
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->users->pluck('name')->join(', ')),
                TextColumn::make('invite_code')
                    ->label('Invite Code')
                    ->searchable(),
                TextColumn::make('expires_at')
                    ->label('Expires At')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('generateInvite')
                    ->label('Invite')
                    ->requiresConfirmation()
                    ->action(fn($record) => self::generateInvite($record))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success'),
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
            'index' => Pages\ListInvites::route('/'),
            'create' => Pages\CreateInvite::route('/create'),
            'edit' => Pages\EditInvite::route('/{record}/edit'),
        ];
    }

    public static function generateInvite($record)
    {
        $inviteCode = Str::random(8);
        $expiresAt = now()->addDays(7);

        $record->update([
            'invite_code' => $inviteCode,
            'expires_at' => $expiresAt,
        ]);

        foreach ($record->users as $user) {
            Mail::to($user->email)->send(new InviteMemberMail(url("/invite/{$inviteCode}")));
        }
    }
}
