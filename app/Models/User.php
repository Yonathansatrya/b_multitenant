<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasTenants
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password', 'current_organization_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke banyak organisasi
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Relasi ke organisasi saat ini
    public function currentOrganization()
    {
        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    // Mendapatkan peran user dalam organisasi saat ini
    public function getRoleInCurrentOrganization()
    {
        $organization = $this->organizations()
            ->where('organization_id', $this->current_organization_id)
            ->first();

        return $organization?->pivot->role ?? null;
    }

    // Mengecek apakah user bisa mengakses organisasi (tenant)
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organizations()->where('organization_id', $tenant->id)->exists();
    }

    // Mendapatkan daftar organisasi yang dapat diakses oleh user
    public function getTenants(Panel $panel): Collection
    {
        return $this->organizations()->get();
    }
}
