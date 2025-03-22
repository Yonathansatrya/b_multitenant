<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MemberInviteController extends Controller
{
    public function showInvitePage($inviteCode)
    {
        $invitation = Invite::where('invite_code', $inviteCode)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        session(['pending_invite' => $inviteCode]);

        $user = User::where('email', $invitation->email)->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('invite.confirm.page', ['invite_code' => $inviteCode]);
        }

        session()->put('invitation_token', $inviteCode);
        return redirect()->route('filament.admin.auth.register', ['email' => $invitation->email])->with('info', 'Silakan buat akun untuk menerima undangan.');
    }

    public function showConfirmPage($inviteCode)
    {
        $invitation = Invite::where('invite_code', $inviteCode)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('invite.confirm', compact('invitation'));
    }

    public function confirmInvite(Request $request, $inviteCode)
    {
        $invitation = Invite::where('invite_code', $inviteCode)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('filament.admin.auth.register')->with('info', 'Silakan buat akun terlebih dahulu.');
        }

        if ($user->organizations()->where('organization_id', $invitation->organization_id)->exists()) {
            return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $invitation->organization->slug])
                ->with('info', 'Anda sudah tergabung dalam organisasi ini.');
        }

        $user->organizations()->attach($invitation->organization_id);
        
        session()->forget('pending_invite');
        session()->save();

        return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $invitation->organization->slug])
            ->with('success', 'Anda telah bergabung dengan organisasi!');
    }
}
