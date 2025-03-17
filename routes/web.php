<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/no-organization', function () {
    return view('no-organization');
})->name('no-organization');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/admin/login');
})->name('logout');
