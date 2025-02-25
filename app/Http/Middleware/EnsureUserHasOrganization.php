<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasOrganization
{
   public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::user() || !Auth::user()->current_organization_id) {
            return redirect()->route('organization.select');
        }
        return $next($request);
    }
}
