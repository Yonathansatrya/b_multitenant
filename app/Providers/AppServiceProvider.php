<?php

namespace App\Providers;

use App\Models\Loan;
use App\Models\LoanItem;
use App\Observers\LoanItemObserver;
use App\Observers\LoanObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Loan::observe(LoanObserver::class);
        LoanItem::observe(LoanItemObserver::class);
    }
}
