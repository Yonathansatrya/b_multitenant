<?php

namespace App\Models;

use Filament\Panel;
use App\Models\OrganizationUser;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements HasTenants
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password', 'current_organization_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke banyak organisasi melalui tabel pivot `organization_user`
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)
                    // ->using(OrganizationUser::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Relasi ke organisasi yang sedang aktif (current organization)
     */
    public function currentOrganization()
    {
        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    /**
     * Mendapatkan role user dalam organisasi yang sedang aktif
     */
    public function getRoleInCurrentOrganization(): ?string
    {
        return $this->organizations()
                    ->where('organizations.id', $this->current_organization_id)
                    ->first()?->pivot->role;
    }

    /**
     * Mengecek apakah user memiliki akses ke tenant (organization tertentu)
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organizations()
                    ->where('organization_id', $tenant->id)
                    ->exists();
    }

    /**
     * Mengambil daftar organisasi yang bisa diakses oleh user
     */
    public function getTenants(Panel $panel): Collection
    {
        return $this->organizations()->get() ?? collect();
    }
}
