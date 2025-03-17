<?php

use App\Models\Invite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invite/{invite_code}', function ($inviteCode) {
    $invitation = Invite::where('invite_code', $inviteCode)
        ->where('expires_at', '>', now())
        ->firstOrFail();

    if (!Auth::check()) {
        return redirect()->to(route('filament.admin.auth.login'))
            ->with('info', 'Silakan login terlebih dahulu untuk menerima undangan.');
    }

    $user = Auth::user();
    $organization = $invitation->organization;

    if ($user->organizations()->where('organization_id', $organization->id)->exists()) {
        return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $organization->slug])
            ->with('info', 'Anda sudah menjadi anggota organisasi ini.');
    }

    $user->organizations()->attach($organization->id);
    $invitation->delete();

    return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $organization->slug])
        ->with('success', 'Anda telah bergabung dengan organisasi!');
})->middleware('auth');

Route::get('/no-organization', function () {
    return view('no-organization');
})->name('no-organization');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/admin/login');
})->name('logout');
