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
                    ->nullable()
                    ->relationship('users', 'name')
                    ->label('User')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->nullable()
                    ->email()
                    ->placeholder('Masukkan email yang ingin diundang')
                    ->requiredWithout('users')
                    ->unique('invites', 'email')
                    ->columnSpanFull(),
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
                    ->label('Kirim ke User')
                    ->searchable()
                    ->formatStateUsing(fn($record) => $record->users->pluck('name')->join(', ')),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
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
                Tables\Actions\Action::make('generateInvite')
                    ->label('Invite')
                    ->requiresConfirmation()
                    ->action(fn($record) => self::generateInvite($record))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success'),
                Tables\Actions\Action::make('copyLink')
                    ->label('Link')
                    ->icon('heroicon-o-link')
                    ->action(function ($record) {
                        $inviteLink = url("/invite/{$record->invite_code}");
                        return \Filament\Notifications\Notification::make()
                            ->title('Link disalin!')
                            ->body("Tautan undangan: $inviteLink")
                            ->success()
                            ->send();
                    })
                    ->extraAttributes(function ($record) {
                        return [
                            'onclick' => "navigator.clipboard.writeText('" . url("/invite/{$record->invite_code}") . "')",
                            'data-invite-code' => $record->invite_code,
                        ];
                    })
                    ->hidden(fn($record) => !$record->invite_code),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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

        if ($record->email) {
            Mail::to($record->email)->send(new InviteMemberMail(url("/invite/{$inviteCode}")));
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin', 'Admin']) ?? false;
    }
}
