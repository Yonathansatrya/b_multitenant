<?php

namespace App\Filament\SuperAdmin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Invite;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Organization;
use App\Mail\InviteMemberMail;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\SuperAdmin\Resources\MemberInviteResource\Pages;
use App\Filament\SuperAdmin\Resources\MemberInviteResource\RelationManagers;

class MemberInviteResource extends Resource
{
    protected static ?string $navigationLabel = 'Member Invite Email';
    protected static ?string $navigationGroup = 'Team';
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
                Forms\Components\Select::make('organization_id')
                    ->label('Undangan dari Organisasi')
                    ->options(Organization::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
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
                Tables\Columns\TextColumn::make('Organization.name')
                    ->label('Undangan Organisasi'),
                Tables\Columns\TextColumn::make('invite_code')
                    ->label('code Invite')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('expiret tanggal')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('generateInvite')
                    ->label('Send')
                    ->requiresConfirmation()
                    ->action(fn($record) => self::generateInvite($record))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success'),
                Tables\Actions\Action::make('copyLink')
                    ->label('Link')
                    ->icon('heroicon-o-link')
                    ->color('blue')
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
                ])
                    ->label('More actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('primary')
                    ->button(),
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
            'index' => Pages\ListMemberInvites::route('/'),
            'create' => Pages\CreateMemberInvite::route('/create'),
            'edit' => Pages\EditMemberInvite::route('/{record}/edit'),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->email) {
            Mail::to($this->record->email)->send(new InviteMemberMail(url("/invite/{$this->record->invite_code}")));
        }
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
}
