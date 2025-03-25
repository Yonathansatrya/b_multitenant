<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutWorkspace extends Page
{
    protected static ?string $navigationLabel = 'Keluar Workspace';
    protected static ?string $navigationIcon = 'heroicon-s-arrow-left-on-rectangle';
    protected static ?string $slug = 'logout-workspace';
    protected static string $view = 'filament.pages.logout-workspace';

    public function logoutWorkSpace()
    {
        $user = Auth::user();

        $currentOrganization = Session::get('current_organization_id'); //kendala ketika ganti organisasi tidak bisa ngikutin dan mengambil yang pertama dalam database
        // $currentOrganization = Organization::where('slug', request()->route('tenant'))->value('id');

        OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $currentOrganization)
            ->delete();

        $recheckOrganization = OrganizationUser::where('user_id', $user->id)->pluck('organization_id');

        if ($recheckOrganization->isNotEmpty()) {
            $changeOrganization = $recheckOrganization->first();
            Session::put('current_organization_id', $changeOrganization);
            $organization = Organization::find($changeOrganization);

            return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $organization->slug]);
        }

        Session::flush();
        Auth::logout();
        return redirect()->route('filament.admin.auth.login');
    }
}
