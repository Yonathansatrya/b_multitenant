<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;

class FilamentPanelProvider extends ServiceProvider
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
    public function boot(): void
    {
        //
    }
}
