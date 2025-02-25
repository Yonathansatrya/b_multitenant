<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function switch($id)
    {
        $user = auth()->user();
        if (!$user->organizations->contains($id)) {
            abort(403, 'Unauthorized');
        }

        $user->update(['current_organization_id' => $id]);

        return redirect()->route('filament.admin.pages.dashboard');
    }
}
