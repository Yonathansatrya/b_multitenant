<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Events\Auth\Registered;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rules\Password;
use Filament\Pages\Auth\Register as AuthRegister;
use Filament\Http\Responses\Auth\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class Register extends AuthRegister
{
    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        $invitationToken = session()->pull('invitation_token', request()->query('invite_code'));

        if (!empty($invitationToken)) {

            session()->put('pending_invite', $invitationToken);
            session()->save();

            return new RegistrationResponse(route('invite.confirm.page', ['invite_code' => $invitationToken]));
        }

        return app(RegistrationResponse::class);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ])
            ->statePath('data');
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('filament-panels::pages/auth/register.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->dehydrateStateUsing(fn($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        return $data;
    }
}
