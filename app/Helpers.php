<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasOrganizationRole')) {
    function hasOrganizationRole($role)
    {
        $user = Auth::user();

        if (!$user || !$user->current_organization_id) {
            return false;
        }

        $currentRole = $user->organizations()
            ->where('organization_id', $user->current_organization_id)
            ->first()
            ->pivot->role ?? null;

        return $currentRole === $role;
    }
}
