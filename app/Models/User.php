<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;

class User extends Authenticatable implements HasTenants
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password', 'current_organization_id'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    protected $with = ['roles'];

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role_id', 'status')
            ->withTimestamps();
    }

    public function currentOrganization()
    {
        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    public function getRoleInCurrentOrganization(): ?Role
    {
        return $this->organizations()
            ->where('organizations.id', $this->current_organization_id)
            ->first()?->pivot->role;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organizations()
            ->where('organization_id', $tenant->id)
            ->exists();
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->organizations()->get() ?? collect();
    }

    public function syncCurrentOrganizationRole()
    {
        $role = $this->getRoleInCurrentOrganization();
        if ($role) {
            $this->syncRoles([$role->name]);
        }
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
