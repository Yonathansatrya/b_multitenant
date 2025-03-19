<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberInviteController extends Controller
{
    public function showInvitePage($inviteCode)
    {
        $invitation = Invite::where('invite_code', $inviteCode)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        session(['pending_invite' => $inviteCode]);
        session()->save();

        $existingUser = User::where('email', $invitation->email)->first();

        if ($existingUser) {
            Auth::login($existingUser);
            return redirect()->route('invite.confirm', ['invite_code' => $inviteCode]);
        } else {
            return redirect()->route('filament.admin.auth.register', ['email' => $invitation->email])->with('info', 'Silakan buat akun untuk menerima undangan.');
        }
    }

    public function confirmInvite(Request $request, $inviteCode)
    {
        $invitation = Invite::where('invite_code', $inviteCode)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('register')->with('info', 'Silakan buat akun terlebih dahulu.');
        }

        if ($user->organizations()->where('organization_id', $invitation->organization_id)->exists()) {
            return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $invitation->organization->slug])
                ->with('info', 'Anda sudah tergabung dalam organisasi ini.');
        }

        $user->organizations()->attach($invitation->organization_id);

        return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $invitation->organization->slug])
            ->with('success', 'Anda telah bergabung dengan organisasi!');
    }
}
