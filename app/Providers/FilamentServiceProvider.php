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
        //
    }
}
