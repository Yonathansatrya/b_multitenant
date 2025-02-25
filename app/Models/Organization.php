<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relasi ke tabel users (banyak ke banyak dengan role tambahan di pivot).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Relasi ke tabel roles (untuk multi-tenant roles).
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'tenant_id');
    }
}
