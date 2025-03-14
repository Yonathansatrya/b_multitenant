<?php

use App\Filament\Pages\CheckRoom;
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\NoOrganization;
use App\Http\Controllers\OrganizationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/no-organization', NoOrganization::class)->name('filament.admin.no-organization');

Route::get('/organization/switch/{id}', [OrganizationController::class, 'switch'])->name('organization.switch');
