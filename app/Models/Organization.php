<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Relasi ke users melalui tabel pivot `organization_user`
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role', 'status')
            ->withTimestamps();
    }

    /**
     * Relasi ke `organization_user` langsung
     */
    public function organizationUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    /**
     * Relasi ke roles dalam organisasi
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'organization_id');
    }
}
