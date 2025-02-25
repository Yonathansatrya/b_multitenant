<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/organization/switch/{id}', [OrganizationController::class, 'switch'])->name('organization.switch');
