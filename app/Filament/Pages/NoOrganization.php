<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class NoOrganization extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation';
    protected static ?string $slug = 'no-organization';
    protected static string $view = 'filament.pages.no-organization';
    public static bool $shouldRegisterNavigation = false;
}
