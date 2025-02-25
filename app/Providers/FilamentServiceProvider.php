<?php

namespace App\Providers;

use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Filament\Http\Middleware\Authenticate;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Panel::configureUsing(function (Panel $panel) {
            $panel->middleware([
                Authenticate::class,
                PermissionMiddleware::class,
            ]);
        });
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->middleware([
                Authenticate::class,
                \Spatie\Permission\Middleware\PermissionMiddleware::class,
            ])
            ->resources([
                \App\Filament\Resources\UserResource::class,
                \App\Filament\Resources\CustomerResource::class,
            ]);
    }
}
