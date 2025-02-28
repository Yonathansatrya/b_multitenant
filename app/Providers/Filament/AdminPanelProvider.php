<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Organization;
use App\Models\User;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\PostsResource;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Tenancy\RegisterOrganization;
use App\Filament\Pages\Tenancy\EditOrganizationProfile;
use App\Filament\Resources\CustomerResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
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
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Dashboard::class,
                // UserResource::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenant(
                model: Organization::class,
                ownershipRelationship: 'tenant',
                slugAttribute: 'slug'
            )
            ->tenantRoutePrefix('organization')
            ->tenantRegistration(RegisterOrganization::class)
            ->tenantProfile(EditOrganizationProfile::class)
            ->tenantMiddleware([
                SyncShieldTenant::class,
            ], isPersistent: true)
            ->plugins([
                FilamentShieldPlugin::make(),
            ]);
    }

    // public function navigationItems(): array
    // {
    //     $user = auth()->user();

    //     if (!$user) {
    //         return [];
    //     }

    //     return [
    //         NavigationGroup::make()
    //             ->label('Organizations')
    //             ->items($user->organizations->map(function ($org) {
    //                 return NavigationItem::make($org->name)
    //                     ->url(route('organization.switch', ['id' => $org->id]));
    //             })->toArray()),
    //     ];
    // }
}
