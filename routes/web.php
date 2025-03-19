<?php

use App\Http\Controllers\MemberInviteController;
use App\Models\Invite;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invite/{invite_code}', [MemberInviteController::class, 'showInvitePage'])->name('invite.confirm');
Route::post('/invite/{invite_code}/confirm', [MemberInviteController::class, 'confirmInvite'])->name('invite.confirm.post');

Route::get('/no-organization', function () {
    return view('no-organization');
})->name('no-organization');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/admin/login');
})->name('logout');
