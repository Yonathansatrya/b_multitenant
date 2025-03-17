<?php

namespace App\Providers\Filament;

use register;
use Filament\Pages;
use Filament\Panel;
use App\Models\User;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Models\Organization;
use Filament\Pages\Dashboard;
use App\Filament\Pages\CheckRoom;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use App\Filament\Pages\NoOrganization;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureOrganization;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use App\Http\Middleware\EnsureUserHasOrganization;
use App\Filament\Pages\Tenancy\RegisterOrganization;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Tenancy\EditOrganizationProfile;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                EnsureOrganization::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenant(
                model: Organization::class,
                ownershipRelationship: 'organization',
                slugAttribute: 'slug'
            )
            ->tenantRoutePrefix('organization')
            ->tenantRegistration(RegisterOrganization::class)
            ->tenantProfile(EditOrganizationProfile::class)
            ->tenantMiddleware([
                SyncShieldTenant::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ], isPersistent: true)
            ->plugins([
                FilamentShieldPlugin::make(),
                \Hasnayeen\Themes\ThemesPlugin::make(),
            ]);
    }

    private function canRegisterOrganization(User $user): bool
    {
        $user = Auth::user();
        return $user && $user->organization_id;
    }
}
