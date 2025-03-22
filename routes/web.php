<?php


use App\Models\Invite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberInviteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invite/{invite_code}', [MemberInviteController::class, 'showInvitePage'])
    ->withoutMiddleware(['ensure.organization'])
    ->name('invite.page');

Route::get('/invite/{invite_code}/confirm', [MemberInviteController::class, 'showConfirmPage'])
    ->withoutMiddleware(['ensure.organization'])
    ->name('invite.confirm.page');

Route::post('/invite/{invite_code}/confirm', [MemberInviteController::class, 'confirmInvite'])
    ->withoutMiddleware(['ensure.organization'])
    ->name('invite.confirm.post');

Route::get('/no-organization', function () {
    return view('no-organization');
})->name('no-organization');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/admin/login');
})->name('logout');
