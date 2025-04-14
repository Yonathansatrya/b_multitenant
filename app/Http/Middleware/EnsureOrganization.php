<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && session()->has('pending_invite') && !$request->routeIs('invite.confirm.page')) {
            return redirect()->route('invite.confirm.page', ['invite_code' => session('pending_invite')]);
        }

        if ($user && $request->routeIs('invite.confirm.page')) {
            session()->forget('pending_invite');
            session()->save();
            return $next($request);
        }

        if ($user && !$user->organizations()->exists() && !session()->has('pending_invite')) {
            return redirect()->route('no-organization');
        }

        return $next($request);
    }
}
