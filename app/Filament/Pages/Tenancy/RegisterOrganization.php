<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Organization;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterOrganization extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Organization';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Organization Name')
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
            ]);
    }

    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);

        // Asumsikan relasi "members" sudah didefinisikan pada model Organization
        $organization->members()->attach(auth()->user());

        return $organization;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasAnyRole(['Super Admin']) ?? false;
    }
}
